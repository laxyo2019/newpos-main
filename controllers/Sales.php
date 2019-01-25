<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

define('PRICE_MODE_STANDARD', 0);
define('PRICE_MODE_KIT', 1);
define('PRICE_MODE_1_RUPEE', 5);

class Sales extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('sales');

		$this->load->library('sale_lib');
		$this->load->library('barcode_lib');
		$this->load->library('email_lib');
		$this->load->library('token_lib');
	}

	public function index()
	{
		$this->_reload();
	}

	public function test($item_id)
	{
		echo $this->Pricing->check_active_offers($item_id);
	}

	public function manage()
	{
		$person_id = $this->session->userdata('person_id');

		if(!$this->Employee->has_grant('reports_sales', $person_id))
		{
			redirect('no_access/sales/reports_sales');
		}
		else
		{
			$data['table_headers'] = get_sales_manage_table_headers();

			// filters that will be loaded in the multiselect dropdown
			if($this->config->item('invoice_enable') == TRUE)
			{
				$data['filters'] = array('only_cash' => $this->lang->line('sales_cash_filter'),
					'only_due' => $this->lang->line('sales_due_filter'),
					'only_check' => $this->lang->line('sales_check_filter'),
					'only_invoices' => $this->lang->line('sales_invoice_filter'));
			}
			else
			{
				$data['filters'] = array('only_cash' => $this->lang->line('sales_cash_filter'),
					'only_due' => $this->lang->line('sales_due_filter'),
					'only_check' => $this->lang->line('sales_check_filter'));
			}

			$this->load->view('sales/manage', $data);
		}
		
	}
	public function sales_invoice()
	{
		//$data['sales_report'] = $this->db->order_by('sale_time',"desc")->get('sales')->result_array();
		$this->load->view('sales/sales_invoice');
	}
	public function get_sale()
	{
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$result_items = array();
		//$data['sales_result'] = $this->db->order_by('sale_time',"desc")->get('sales')->result_array();
		$this->db->select('
		sales.sale_id AS sale_id,
		sales.sale_time AS sale_time,
		sales.customer_id AS customer_id,
		sales.tally_number AS tally_number,
		sales.invoice_number AS invoice_number,
		sales.employee_id AS employee_id,
		sales.sale_status AS sale_status,
		sales.sale_type AS sale_type,
		sales.bill_type AS bill_type,
		sales_items.item_id AS item_id,
		sales_items.quantity_purchased AS quantity,
		sales_items.item_unit_price AS item_price,
		sales_items.discount_percent AS item_discount,
		sales_payments.payment_amount AS payment_amount
		');
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id');
		$this->db->join('sales_payments', 'sales_payments.sale_id = sales.sale_id');
		$this->db->where('DATE(sale_time) BETWEEN "'.rawurldecode($start_date).'" AND "'.rawurldecode($end_date).'"');
		$data['sales_results'] = $this->db->get()->result_array();
		$this->load->view('sales/get_sales_invoice', $data);
	}


	public function invoice_excel($sale_id)
	{
		$data = $this->_load_sale_data($sale_id);
		$this->load->view('sales/invoice_excel', $data);
		$this->sale_lib->clear_all();
	}

	public function get_row($row_id)
	{
		$sale_info = $this->Sale->get_info($row_id)->row();
		$data_row = $this->xss_clean(get_sale_data_row($sale_info));

		echo json_encode($data_row);
	}

	public function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$filters = array(
			'sale_type' => 'all',
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'),
			'only_cash' => FALSE,
			'only_due' => FALSE,
			'only_check' => FALSE,
			'only_invoices' => $this->config->item('invoice_enable') && $this->input->get('only_invoices'),
			'is_valid_receipt' => $this->Sale->is_valid_receipt($search)
		);
		
		$filters['location_id'] = ($this->Item->check_auth(array('admin', 'superadmin', 'accounts'))) ? "all" : $this->Stock_location->get_location_id_2($this->session->userdata('person_id'));

		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);

		$sales = $this->Sale->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Sale->get_found_rows($search, $filters);
		$payments = $this->Sale->get_payments_summary($search, $filters);
		$payment_summary = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales));

		$data_rows = array();
		foreach($sales->result() as $sale)
		{
			$data_rows[] = $this->xss_clean(get_sale_data_row($sale));
		}

		if($total_rows > 0)
		{
			$data_rows[] = $this->xss_clean(get_sale_data_last_row($sales));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'payment_summary' => $payment_summary));
	}

	public function item_search()
	{
		$suggestions = array();
		$receipt = $search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;

		if($this->sale_lib->get_mode() == 'return')
		{
			if($this->Sale->is_valid_receipt($receipt))
			{
				if($response = $this->Sale->is_returnable($receipt))
				{
					$suggestions[] = $response;
				}
				else
				{
					// if a valid receipt or invoice was found the search term will be replaced with a receipt number (POS #)
					$suggestions[] = $receipt;
				}
			}
		}
		else
		{
			$suggestions = array_merge($suggestions, $this->Item->get_search_suggestions($search, array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE));
			$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($search));

			$suggestions = $this->xss_clean($suggestions);
		}
		
		echo json_encode($suggestions);
	}

	public function suggest_search()
	{
		$search = $this->input->post('term') != '' ? $this->input->post('term') : NULL;

		$suggestions = $this->xss_clean($this->Sale->get_search_suggestions($search));

		echo json_encode($suggestions);
	}

	public function select_customer()
	{
		$customer_id = $this->input->post('customer');
		if($this->Customer->exists($customer_id))
		{
			$this->sale_lib->set_customer($customer_id);
			// $discount_percent = $this->Customer->get_info($customer_id)->discount_percent;

			// apply customer default discount to items that have 0 discount
			// if($discount_percent != '')
			// {
			// 	$this->sale_lib->apply_customer_discount($discount_percent);
			// }
		}

		$this->_reload();
	}

	public function quick_billing()
	{
		$this->load->view('sales/form_quick_billing', NULL);
	}

	public function do_quick_billing()
	{
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{
			if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
			{
				// Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$discount = 0;
					$customer_id = $this->sale_lib->get_customer();
			
					$item_id_or_number_or_item_kit_or_receipt = $data[0];
					$this->barcode_lib->parse_barcode_fields($quantity, $item_id_or_number_or_item_kit_or_receipt);
					$mode = $this->sale_lib->get_mode();
					$quantity = ($mode == 'return') ? -$quantity : $quantity;
					$item_location = $this->sale_lib->get_sale_location();

					$this->sale_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity, $item_location, $discount, PRICE_MODE_STANDARD);

					++$i;

				} // while loop ends here

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
			}
		}
	}

	public function change_mode()
	{
		$mode = $this->input->post('mode');
		$this->sale_lib->set_mode($mode);

		if($mode == 'sale')
		{
			$this->sale_lib->set_sale_type(SALE_TYPE_POS);
			$this->sale_lib->remove_return_sale_id();
		}
		else if($mode == 'sale_quote')
		{
			$this->sale_lib->set_sale_type(SALE_TYPE_QUOTE);
		}
		else if($mode == 'sale_work_order')
		{
			$this->sale_lib->set_sale_type(SALE_TYPE_WORK_ORDER);
		}
		else if($mode == 'sale_invoice')
		{
			$this->sale_lib->set_sale_type(SALE_TYPE_INVOICE);
			$this->sale_lib->remove_return_sale_id();
		}
		else
		{
			$this->sale_lib->set_sale_type(SALE_TYPE_RETURN);
			$this->sale_lib->remove_invoice_mode();
		}

		$stock_location = $this->input->post('stock_location');
		if(!$stock_location || $stock_location == $this->sale_lib->get_sale_location())
		{
			$dinner_table = $this->input->post('dinner_table');
			$this->sale_lib->set_dinner_table($dinner_table);

		}
		elseif($this->Stock_location->is_allowed_location($stock_location, 'sales'))
		{
			$this->sale_lib->set_sale_location($stock_location);
		}

		$this->_reload();
	}

	public function get_cashier_id($sale_id)
	{
		return $this->db->where('sale_id', $sale_id)->get('sales')->row('cashier_id');
	}

	public function cashier_auth()
	{
		$cashier_id = $this->input->post('cashier_id');
		$webkey = $this->input->post('webkey');
		$db_webkey = $this->db->where('id', $cashier_id)->get('cashiers')->row()->webkey;
		if($webkey == $db_webkey)
		{
			echo "success";
		}
	}

	public function set_cashier()
	{
		$this->session->set_userdata('cashier_id', $this->input->post('cashier_id'));
	}

	public function change_register_mode($sale_type)
	{
		if($sale_type == SALE_TYPE_POS)
		{
			$this->sale_lib->set_mode('sale');
		}
		elseif($sale_type == SALE_TYPE_QUOTE)
		{
			$this->sale_lib->set_mode('sale_quote');
		}
		elseif($sale_type == SALE_TYPE_WORK_ORDER)
		{
			$this->sale_lib->set_mode('sale_work_order');
		}
		elseif($sale_type == SALE_TYPE_INVOICE)
		{
			$this->sale_lib->set_mode('sale_invoice');
		}
		elseif($sale_type == SALE_TYPE_RETURN)
		{
			$this->sale_lib->set_mode('return');
		}
		else
		{
			$this->sale_lib->set_mode('sale');
		}
	}

	public function set_comment()
	{
		$this->sale_lib->set_comment($this->input->post('comment'));
	}

	public function set_invoice_number()
	{
		$this->sale_lib->set_invoice_number($this->input->post('sales_invoice_number'));
	}

	public function set_invoice_number_enabled()
	{
		$this->sale_lib->set_invoice_number_enabled($this->input->post('sales_invoice_number_enabled'));
	}

	public function set_payment_type()
	{
		$this->sale_lib->set_payment_type($this->input->post('selected_payment_type'));
		$this->_reload();
	}

	public function set_print_after_sale()
	{
		$this->sale_lib->set_print_after_sale($this->input->post('sales_print_after_sale'));
	}

	public function set_price_work_orders()
	{
		$this->sale_lib->set_price_work_orders($this->input->post('price_work_orders'));
	}

	public function set_email_receipt()
	{
		$this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	// Multiple Payments
	public function add_payment()
	{
		$data = array();

		$payment_type = $this->input->post('payment_type');
		if($payment_type != $this->lang->line('sales_giftcard'))
		{
			$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'trim|required|callback_numeric');
		}
		else
		{
			$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'trim|required');
		}

		if($this->form_validation->run() == FALSE)
		{
			if($payment_type == $this->lang->line('sales_giftcard'))
			{
				$data['error'] = $this->lang->line('sales_must_enter_numeric_giftcard');
			}
			else
			{
				$data['error'] = $this->lang->line('sales_must_enter_numeric');
			}
		}
		else
		{
			if($payment_type == $this->lang->line('sales_giftcard'))
			{
				// in case of giftcard payment the register input amount_tendered becomes the giftcard number
				$giftcard_num = $this->input->post('amount_tendered');

				$payments = $this->sale_lib->get_payments();
				$payment_type = $payment_type . ':' . $giftcard_num;
				$current_payments_with_giftcard = isset($payments[$payment_type]) ? $payments[$payment_type]['payment_amount'] : 0;
				$cur_giftcard_value = $this->Giftcard->get_giftcard_value($giftcard_num);
				$cur_giftcard_customer = $this->Giftcard->get_giftcard_customer($giftcard_num);
				$customer_id = $this->sale_lib->get_customer();
				if(isset($cur_giftcard_customer) && $cur_giftcard_customer != $customer_id)
				{
					$data['error'] = $this->lang->line('giftcards_cannot_use', $giftcard_num);
				}
				elseif(($cur_giftcard_value - $current_payments_with_giftcard) <= 0 && $this->sale_lib->get_mode() == 'sale')
				{
					$data['error'] = $this->lang->line('giftcards_remaining_balance', $giftcard_num, to_currency($cur_giftcard_value));
				}
				else
				{
					$new_giftcard_value = $this->Giftcard->get_giftcard_value($giftcard_num) - $this->sale_lib->get_amount_due();
					$new_giftcard_value = $new_giftcard_value >= 0 ? $new_giftcard_value : 0;
					$this->sale_lib->set_giftcard_remainder($new_giftcard_value);
					$new_giftcard_value = str_replace('$', '\$', to_currency($new_giftcard_value));
					$data['warning'] = $this->lang->line('giftcards_remaining_balance', $giftcard_num, $new_giftcard_value);
					$amount_tendered = min($this->sale_lib->get_amount_due(), $this->Giftcard->get_giftcard_value($giftcard_num));

					$this->sale_lib->add_payment($payment_type, $amount_tendered);
				}
			}
			elseif($payment_type == $this->lang->line('sales_rewards'))
			{
				$customer_id = $this->sale_lib->get_customer();
				$package_id = $this->Customer->get_info($customer_id)->package_id;
				if(!empty($package_id))
				{
					$package_name = $this->Customer_rewards->get_name($package_id);
					$points = $this->Customer->get_info($customer_id)->points;
					$points = ($points == NULL ? 0 : $points);

					$payments = $this->sale_lib->get_payments();
					$payment_type = $payment_type;
					$current_payments_with_rewards = isset($payments[$payment_type]) ? $payments[$payment_type]['payment_amount'] : 0;
					$cur_rewards_value = $points;

					if(($cur_rewards_value - $current_payments_with_rewards) <= 0)
					{
						$data['error'] = $this->lang->line('rewards_remaining_balance') . to_currency($cur_rewards_value);
					}
					else
					{
						$new_reward_value = $points - $this->sale_lib->get_amount_due();
						$new_reward_value = $new_reward_value >= 0 ? $new_reward_value : 0;
						$this->sale_lib->set_rewards_remainder($new_reward_value);
						$new_reward_value = str_replace('$', '\$', to_currency($new_reward_value));
						$data['warning'] = $this->lang->line('rewards_remaining_balance'). $new_reward_value;
						$amount_tendered = min($this->sale_lib->get_amount_due(), $points);

						$this->sale_lib->add_payment($payment_type, $amount_tendered);
					}
				}
			}
			else
			{
				$amount_tendered = $this->input->post('amount_tendered');
				$this->sale_lib->add_payment($payment_type, $amount_tendered);
			}
		}

		$this->_reload($data);
	}
	
	public function add_credit_note_payment()
	{
		$credit_details = $this->check_my_credit($this->session->userdata('sales_customer'));
		$cn_sale_id = $credit_details[0]['cn_sale_id'];
		$credit_note_number = $credit_details[0]['cn_number'];

		$vc_amount = $this->Sale->is_vc_applied_sale($cn_sale_id);

		$cn_amount = (empty($vc_amount)) ? $this->db->where('sale_id', $cn_sale_id)->get('sales_payments')->row()->payment_amount : $this->db->where('sale_id', $cn_sale_id)->get('sales_payments')->row()->payment_amount + $vc_amount;
		$cn_amount *= -1; // to convert into positive amount

		$this->sale_lib->add_payment($this->lang->line('sales_credit_note'), $cn_amount);
		$this->sale_lib->apply_credit_note($credit_note_number);
	}

	public function add_special_voucher_payment($voucher_id, $amount)
	{
		// $vc_data = $this->db->where('id', $voucher_id)->get('special_vc')->row();
		$this->sale_lib->add_payment($this->lang->line('sales_special_voucher'), $amount);
		$this->sale_lib->apply_special_voucher(3); //WINTER10
	}

	// Multiple Payments
	public function delete_payment($payment_id)
	{
		$this->sale_lib->delete_payment($payment_id);
		if($payment_id == $this->lang->line('sales_credit_note'))
		{
			$this->sale_lib->remove_credit_note();
		}
		if($payment_id == "Special Voucher")
		{
			$this->sale_lib->remove_special_voucher();
		}

		$this->_reload();
	}

	// 3 Custom Functions
	public function set_billing_type(){
		$this->session->set_userdata('billtype', $this->input->post('type'));
	}

	public function set_taxing_type(){
		$this->session->set_userdata('taxtype', $this->input->post('type'));
	}

	public function set_franchise_customer(){
		$customer_id = $this->input->post('customer_id');
		if($this->Customer->exists($customer_id))
		{
			$this->sale_lib->set_customer($customer_id);
		}
	}

	public function get_custom_fields($tag, $table)
	{
		$this->db->where('tag', $tag);
		return $this->db->get($table)->result_array();
	}

	public function add()
	{
		$data = array();

		$discount = 0;

		$customer_id = $this->sale_lib->get_customer();

		$item_id_or_number_or_item_kit_or_receipt = $this->input->post('item');
		$this->barcode_lib->parse_barcode_fields($quantity, $item_id_or_number_or_item_kit_or_receipt);
		$mode = $this->sale_lib->get_mode();
		$quantity = ($mode == 'return') ? -$quantity : $quantity;
		$item_location = $this->sale_lib->get_sale_location();

		if($mode == 'return' && $this->Sale->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->sale_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->Item_kit->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			// Add kit item to order if one is assigned
			$pieces = explode(' ', $item_id_or_number_or_item_kit_or_receipt);
			$item_kit_id = $pieces[1];
			$item_kit_info = $this->Item_kit->get_info($item_kit_id);
			$kit_item_id = $item_kit_info->kit_item_id;
			$kit_price_option = $item_kit_info->price_option;
			$kit_print_option = $item_kit_info->print_option; // 0-all, 1-priced, 2-kit-only

			if($item_kit_info->kit_discount_percent != 0 && $item_kit_info->kit_discount_percent > $discount)
			{
				$discount = $item_kit_info->kit_discount_percent;
			}

			$price = NULL;
			$print_option = PRINT_ALL; // Always include in list of items on invoice

			if(!empty($kit_item_id))
			{
				if(!$this->sale_lib->add_item($kit_item_id, $quantity, $item_location, $discount, PRICE_MODE_STANDARD))
				{
					$data['error'] = $this->lang->line('sales_unable_to_add_item');
				}
				else
				{
					$data['warning'] = $this->sale_lib->out_of_stock($item_kit_id, $item_location);
				}
			}

			// Add item kit items to order
			$stock_warning = NULL;
			if(!$this->sale_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $item_location, $discount, $kit_price_option, $kit_print_option, $stock_warning))
			{
				$data['error'] = $this->lang->line('sales_unable_to_add_item');
			}
			elseif($stock_warning != NULL)
			{
				$data['warning'] = $stock_warning;
			}
		}
		else
		{
			if(!$this->sale_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity, $item_location, $discount, PRICE_MODE_STANDARD))
			{
				$data['error'] = $this->lang->line('sales_unable_to_add_item');
			}
			else
			{
				$data['warning'] = $this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt, $item_location);
			}
		}
		$this->_reload($data);
	}

	public function add_custom()
	{
		$data = array();

		$discount = 0;

		$customer_id = $this->sale_lib->get_customer();

		$item_id_or_number_or_item_kit_or_receipt = $this->input->post('item');
		$this->barcode_lib->parse_barcode_fields($quantity, $item_id_or_number_or_item_kit_or_receipt);
		$mode = $this->sale_lib->get_mode();
		$quantity = ($mode == 'return') ? -$quantity : $quantity;
		$item_location = $this->sale_lib->get_sale_location();

		if(!$this->sale_lib->add_item_custom($item_id_or_number_or_item_kit_or_receipt, $quantity, $item_location, $discount, PRICE_MODE_STANDARD))
		{
			$data['error'] = $this->lang->line('sales_unable_to_add_item');
		}
		else
		{
			$data['warning'] = $this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt, $item_location);
		}

		$this->_reload($data);
	}

	public function edit_item($item_id)
	{
		$data = array();

		$this->form_validation->set_rules('price', 'lang:sales_price', 'required|callback_numeric');
		$this->form_validation->set_rules('quantity', 'lang:sales_quantity', 'required|callback_numeric');
		$this->form_validation->set_rules('discount', 'lang:sales_discount', 'required|callback_numeric');

		$description = $this->input->post('description');
		$serialnumber = $this->input->post('serialnumber');
		$price = parse_decimals($this->input->post('price'));
		$quantity = parse_decimals($this->input->post('quantity'));
		$discount = parse_decimals($this->input->post('discount'));
		$item_location = $this->input->post('location');
		$discounted_total = $this->input->post('discounted_total') != '' ? $this->input->post('discounted_total') : NULL;

		if($this->form_validation->run() != FALSE)
		{
			$this->sale_lib->edit_item($item_id, $description, $serialnumber, $quantity, $discount, $price, $discounted_total);
		}
		else
		{
			$data['error'] = $this->lang->line('sales_error_editing_item');
		}

		$data['warning'] = $this->sale_lib->out_of_stock($this->sale_lib->get_item_id($item_id), $item_location);

		$this->_reload($data);
	}

	public function delete_item($item_number)
	{
		$this->sale_lib->delete_item($item_number);

		$this->_reload();
	}

	public function remove_customer()
	{
		$this->sale_lib->clear_giftcard_remainder();
		$this->sale_lib->clear_rewards_remainder();
		$this->sale_lib->delete_payment($this->lang->line('sales_rewards'));
		$this->sale_lib->clear_invoice_number();
		$this->sale_lib->clear_quote_number();
		$this->sale_lib->remove_customer();

		$this->_reload();
	}

	public function complete()
	{
		$sale_id = $this->sale_lib->get_sale_id();
		$sale_type = $this->sale_lib->get_sale_type();
		$data = array();

		$cashier_id = $this->session->userdata('cashier_id');
		$data['cashier_name'] = $this->Sale->get_cashier_detail($cashier_id, 'name');
		$data['cashier_sale_code'] = $this->Sale->get_cashier_detail($cashier_id, 'id');

		$tally_number = $this->Sale->tally_number_factory();
		$data['tally_number'] = $tally_number;

		$data['dinner_table'] = $this->sale_lib->get_dinner_table();
		$data['cart'] = $this->sale_lib->get_cart();
		$data['transaction_time'] = date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'));
		$data['transaction_date'] = date($this->config->item('dateformat'));
		$data['show_stock_locations'] = $this->Stock_location->show_locations('sales');
		$data['comments'] = $this->sale_lib->get_comment();
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$employee_info = $this->Employee->get_info($employee_id);
		$data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name[0];
		$data['company_info'] = implode("\n", array(
			$this->config->item('address'),
			$this->config->item('phone'),
			$this->config->item('account_number')
		));
		$data['invoice_number_enabled'] = $this->sale_lib->is_invoice_mode();
		$data['cur_giftcard_value'] = $this->sale_lib->get_giftcard_remainder();
		$data['cur_rewards_value'] = $this->sale_lib->get_rewards_remainder();
		$data['print_after_sale'] = $this->sale_lib->is_print_after_sale();
		$data['price_work_orders'] = $this->sale_lib->is_price_work_orders();
		$data['email_receipt'] = $this->sale_lib->is_email_receipt();
		$customer_id = $this->sale_lib->get_customer();
		$invoice_number_enabled = $this->sale_lib->get_invoice_number_enabled();
		$invoice_number = $this->sale_lib->get_invoice_number();
		$data["invoice_number"] = $invoice_number;
		$work_order_number = $this->sale_lib->get_work_order_number();
		$data["work_order_number"] = $work_order_number;
		$quote_number = $this->sale_lib->get_quote_number();
		$data["quote_number"] = $quote_number;
		$customer_info = $this->_load_customer_data($customer_id, $data);
		if(!empty($customer_info->comments))
		{
			$data["customer_comments"] = $customer_info->comments;
		}
		$data['taxes'] = $this->sale_lib->get_taxes();
		$data['discount'] = $this->sale_lib->get_discount();
		$data['payments'] = $this->sale_lib->get_payments();

		// Returns 'subtotal', 'total', 'cash_total', 'payment_total', 'amount_due', 'cash_amount_due', 'payments_cover_total'
		$totals = $this->sale_lib->get_totals();
		$data['total_units'] = $totals['total_units'];
		$data['subtotal'] = $totals['subtotal'];
		$data['total'] = $totals['total'];
		$data['payments_total'] = $totals['payment_total'];
		$data['payments_cover_total'] = $totals['payments_cover_total'];
		$data['cash_rounding'] = $this->session->userdata('cash_rounding');
		$data['prediscount_subtotal'] = $totals['prediscount_subtotal'];
		$data['cash_total'] = $totals['cash_total'];
		$data['non_cash_total'] = $totals['total'];
		$data['cash_amount_due'] = $totals['cash_amount_due'];
		$data['non_cash_amount_due'] = $totals['amount_due'];

		if($data['cash_rounding'])
		{
			$data['total'] = $totals['cash_total'];
			$data['amount_due'] = $totals['cash_amount_due'];
		}
		else
		{
			$data['total'] = $totals['total'];
			$data['amount_due'] = $totals['amount_due'];
		}
		$data['amount_change'] = $data['amount_due'] * -1;

		$data['print_price_info'] = TRUE;

		$override_invoice_number = NULL;

		if($this->sale_lib->is_sale_by_receipt_mode() && $invoice_number_enabled )
		{
			$pos_invoice = TRUE;
			$candidate_invoice_number = $invoice_number;
			if($candidate_invoice_number != NULL && strlen($candidate_invoice_number) > 3)
			{
				if(strpos($candidate_invoice_number, '{') == FALSE)
				{
					$override_invoice_number = $candidate_invoice_number;
				}
			}
		}
		else
		{
			$pos_invoice = FALSE;
		}

		if($this->sale_lib->is_invoice_mode() || $pos_invoice)
		{
			// generate final invoice number (if using the invoice in sales by receipt mode then the invoice number can be manually entered or altered in some way
			if($pos_invoice)
			{
				// The user can retain the default encoded format or can manually override it.  It still passes through the rendering step.
				$this->sale_lib->set_invoice_number($this->input->post('invoice_number'), $keep_custom = TRUE);
				$invoice_format = $this->sale_lib->get_invoice_number();
				// If the user blanks out the invoice number and doesn't put anything in there then revert back to the default format encoding
				if(empty($invoice_format))
				{
					$invoice_format = $this->config->item('sales_invoice_format');
				}
			}
			else
			{
				$invoice_format = $this->config->item('sales_invoice_format');
			}

			if($override_invoice_number == NULL)
			{
				$invoice_number = $this->token_lib->render($invoice_format);
			}
			else
			{
				$invoice_number = $override_invoice_number;
			}

			if($sale_id == -1 && $this->Sale->check_invoice_number_exists($invoice_number))
			{
				$data['error'] = $this->lang->line('sales_invoice_number_duplicate');
				$this->_reload($data);
			}
			else
			{
				$data['invoice_number'] = $invoice_number;
				$data['sale_status'] = COMPLETED;
				$sale_type = SALE_TYPE_INVOICE;

				// Save the data to the sales table
				$data['sale_id_num'] = $this->Sale->save($sale_id, $data['sale_status'], $data['cart'], $customer_id, $employee_id, $data['comments'], $invoice_number, $tally_number, $cashier_id, $work_order_number, $quote_number, $sale_type, $data['payments'], $data['dinner_table'], $data['taxes']);
				$data['sale_id'] = 'POS ' . $data['sale_id_num'];

				if(!empty($this->session->userdata('applied_credit_note'))) //check in session if credit note is already applied
				{
					$redeem_data = array( //update entry in ospos_sales_returns table
						'redeem_sale_id' => $data['sale_id_num'],
						'redeem_time' => date('Y-m-d H:i:s'),
						'status' => 1
					);

					$this->db->where(
							array(
								'customer_id' => $this->Sale->get_customer($data['sale_id_num'])->person_id,
								'cn_number' => $this->session->userdata('applied_credit_note')
							)
						)->update('sales_returns', $redeem_data);
				}

				$earned_voucher_id = $this->session->userdata('earned_voucher_id');
				if(!empty($earned_voucher_id))
				{
					$this->store_reward_vc($earned_voucher_id, $data['sale_id_num']);
				}

				$redeem_voucher_id = $this->session->userdata('redeem_voucher_id');
				if(!empty($redeem_voucher_id))
				{
					$this->redeem_reward_vc($redeem_voucher_id, $data['sale_id_num']);
				}
					
				// Resort and filter cart lines for printing
				$data['cart'] = $this->sale_lib->sort_and_filter_cart($data['cart']);

				$data = $this->xss_clean($data);

				if($data['sale_id_num'] == -1)
				{
					$data['error_message'] = $this->lang->line('sales_transaction_failed');
				}
				else
				{
					$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['sale_id']);

					// Switching to daily sales invoice to avoid errors
					$this->invoice($data['sale_id_num']);
					//$this->load->view('sales/invoice', $data); //INVOICE WHICH IS SHOWN JUST AFTER SALES
					//$this->sale_lib->clear_all();
				}
			}
		}
		elseif($this->sale_lib->is_work_order_mode())
		{

			if(!($data['price_work_orders'] == 1))
			{
				$data['print_price_info'] = FALSE;
			}

			$data['sales_work_order'] = $this->lang->line('sales_work_order');
			$data['work_order_number_label'] = $this->lang->line('sales_work_order_number');

			if($work_order_number == NULL)
			{
				// generate work order number
				$work_order_format = $this->config->item('work_order_format');
				$work_order_number = $this->token_lib->render($work_order_format);
			}

			if($sale_id == -1 && $this->Sale->check_work_order_number_exists($work_order_number))
			{
				$data['error'] = $this->lang->line('sales_work_order_number_duplicate');
				$this->_reload($data);
			}
			else
			{
				$data['work_order_number'] = $work_order_number;
				$data['sale_status'] = SUSPENDED;
				$sale_type = SALE_TYPE_WORK_ORDER;

				$data['sale_id_num'] = $this->Sale->save($sale_id, $data['sale_status'], $data['cart'], $customer_id, $employee_id, $data['comments'], $invoice_number, $cashier_id, $work_order_number, $quote_number, $sale_type, $data['payments'], $data['dinner_table'], $data['taxes']);
				$this->sale_lib->set_suspended_id($data['sale_id_num']);

				$data['cart'] = $this->sale_lib->sort_and_filter_cart($data['cart']);

				$data = $this->xss_clean($data);

				$data['barcode'] = NULL;

				$this->load->view('sales/work_order', $data);
				$this->sale_lib->clear_mode();
				$this->sale_lib->clear_all();
			}
		}
		elseif($this->sale_lib->is_quote_mode())
		{
			$data['sales_quote'] = $this->lang->line('sales_quote');
			$data['quote_number_label'] = $this->lang->line('sales_quote_number');

			if($quote_number == NULL)
			{
				// generate quote number
				$quote_format = $this->config->item('sales_quote_format');
				$quote_number = $this->token_lib->render($quote_format);
			}

			if($sale_id == -1 && $this->Sale->check_quote_number_exists($quote_number))
			{
				$data['error'] = $this->lang->line('sales_quote_number_duplicate');
				$this->_reload($data);
			}
			else
			{
				$data['quote_number'] = $quote_number;
				$data['sale_status'] = SUSPENDED;
				$sale_type = SALE_TYPE_QUOTE;

				$data['sale_id_num'] = $this->Sale->save($sale_id, $data['sale_status'], $data['cart'], $customer_id, $employee_id, $data['comments'], $invoice_number, $cashier_id, $work_order_number, $quote_number, $sale_type, $data['payments'], $data['dinner_table'], $data['taxes']);
				$this->sale_lib->set_suspended_id($data['sale_id_num']);

				$data['cart'] = $this->sale_lib->sort_and_filter_cart($data['cart']);

				$data = $this->xss_clean($data);

				$data['barcode'] = NULL;

				$this->load->view('sales/quote', $data);
				$this->sale_lib->clear_mode();
				$this->sale_lib->clear_all();
			}
		}
		else
		{
			// Save the data to the sales table
			$data['sale_status'] = COMPLETED;
			if($this->sale_lib->is_return_mode())
			{
				$sale_type = SALE_TYPE_RETURN;
			}
			else
			{
				$sale_type = SALE_TYPE_POS;
			}

			$credit_note_number = $this->Sale->credit_note_factory();
			$data['credit_note_number'] = $credit_note_number;
			$return_sale_id = $this->session->userdata('return_sale_id');
			
			$data['sale_id_num'] = $this->Sale->save($sale_id, $data['sale_status'], $data['cart'], $customer_id, $employee_id, $data['comments'], $credit_note_number, $tally_number, $cashier_id, $work_order_number, $quote_number, $sale_type, $data['payments'], $data['dinner_table'], $data['taxes']);

			$data['ref_invoice_number'] = $this->Sale->get_ref_invoice_number($return_sale_id);

			//one entry in ospos_sales_returns table for credit note creation
			$return_sale_data = array(
				'emp_id'	=> $employee_id,
				'customer_id' => $this->Sale->get_customer($return_sale_id)->person_id,
				'return_sale_id' => $return_sale_id, // #ref sale_id
				'cn_sale_id' => $data['sale_id_num'],
				'cn_number' => $credit_note_number,
				'cn_time' => date('Y-m-d H:i:s')
			);
			$this->db->insert('sales_returns', $return_sale_data);

			$data['sale_id'] = 'POS ' . $data['sale_id_num'];

			$data['cart'] = $this->sale_lib->sort_and_filter_cart($data['cart']);
			$data = $this->xss_clean($data);

			if($data['sale_id_num'] == -1)
			{
				$data['error_message'] = $this->lang->line('sales_transaction_failed');
			}
			else
			{
				$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['sale_id']);

				// Reload (sorted) and filter the cart line items for printing purposes
				$data['cart'] = $this->get_filtered($this->sale_lib->get_cart_reordered($data['sale_id_num']));

				$this->credit_note($data['sale_id_num']);
				// $this->load->view('sales/credit_note', $data);
				// $this->sale_lib->clear_all();
			}
		}
	}

	public function send_pdf($sale_id, $type = 'invoice')
	{
		$sale_data = $this->_load_sale_data($sale_id);

		$result = FALSE;
		$message = $this->lang->line('sales_invoice_no_email');

		if(!empty($sale_data['customer_email']))
		{
			$to = $sale_data['customer_email'];
			$number = $sale_data[$type."_number"];
			$subject = $this->lang->line("sales_$type") . ' ' . $number;

			$text = $this->config->item('invoice_email_message');
			$tokens = array(new Token_invoice_sequence($sale_data['invoice_number']),
				new Token_invoice_count('POS ' . $sale_data['sale_id']),
				new Token_customer((object)$sale_data));
			$text = $this->token_lib->render($text, $tokens);

			// generate email attachment: invoice in pdf format
			$html = $this->load->view("sales/" . $type . "_email", $sale_data, TRUE);
			// load pdf helper
			$this->load->helper(array('dompdf', 'file'));
			$filename = sys_get_temp_dir() . '/' . $this->lang->line("sales_$type") . '-' . str_replace('/', '-', $number) . '.pdf';
			if(file_put_contents($filename, pdf_create($html)) !== FALSE)
			{
				$result = $this->email_lib->sendEmail($to, $subject, $text, $filename);
			}

			$message = $this->lang->line($result ? "sales_" . $type . "_sent" : "sales_" . $type . "_unsent") . ' ' . $to;
		}

		echo json_encode(array('success' => $result, 'message' => $message, 'id' => $sale_id));

		$this->sale_lib->clear_all();

		return $result;
	}

	public function send_receipt($sale_id)
	{
		$sale_data = $this->_load_sale_data($sale_id);

		$result = FALSE;
		$message = $this->lang->line('sales_receipt_no_email');

		if(!empty($sale_data['customer_email']))
		{
			$sale_data['barcode'] = $this->barcode_lib->generate_receipt_barcode($sale_data['sale_id']);

			$to = $sale_data['customer_email'];
			$subject = $this->lang->line('sales_receipt');

			$text = $this->load->view('sales/receipt_email', $sale_data, TRUE);

			$result = $this->email_lib->sendEmail($to, $subject, $text);

			$message = $this->lang->line($result ? 'sales_receipt_sent' : 'sales_receipt_unsent') . ' ' . $to;
		}

		echo json_encode(array('success' => $result, 'message' => $message, 'id' => $sale_id));

		$this->sale_lib->clear_all();

		return $result;
	}

	public function check_my_credit($customer_id)
	{
		$available_credits = array();
		$this->db->where('customer_id', $customer_id);
		$this->db->where('deleted', 0);
		$this->db->where('emp_id', $this->session->userdata('person_id')); //CN scanning limited to specific shop
		foreach($this->db->get('sales_returns')->result_array() as $row)
		{
			if($this->Sale->get_days_ago($row['cn_time']) <= 15 && $row['status'] == 0) //check if credit note is not expired or redeemed
			{
				$available_credits[] = $row;
			}
		}
		return $available_credits;
	}

	private function _load_customer_data($customer_id, &$data, $stats = FALSE)
	{
		$customer_info = '';

		if($customer_id != -1)
		{
			$customer_info = $this->Customer->get_info($customer_id);
			$data['customer_id'] = $customer_id;
			if(!empty($customer_info->company_name))
			{
				$data['customer'] = $customer_info->company_name;
			}
			else
			{
				$data['customer'] = $customer_info->first_name . ' ' . $customer_info->last_name;
			}
			$data['first_name'] = $customer_info->first_name;
			$data['last_name'] = $customer_info->last_name;
			$data['customer_email'] = $customer_info->email;
			$data['customer_address'] = $customer_info->address_1;
			if(!empty($customer_info->zip) || !empty($customer_info->city))
			{
				$data['customer_location'] = $customer_info->zip . ' ' . $customer_info->city;
			}
			else
			{
				$data['customer_location'] = '';
			}
			$data['customer_account_number'] = $customer_info->account_number;
			$data['customer_gstin'] = $customer_info->gstin;
			// $data['customer_discount_percent'] = $customer_info->discount_percent;
			$package_id = $this->Customer->get_info($customer_id)->package_id;
			if($package_id != NULL)
			{
				$package_name = $this->Customer_rewards->get_name($package_id);
				$points = $this->Customer->get_info($customer_id)->points;
				$data['customer_rewards']['package_id'] = $package_id;
				$data['customer_rewards']['points'] = empty($points) ? 0 : $points;
				$data['customer_rewards']['package_name'] = $package_name;
			}

			if($stats)
			{
				$cust_stats = $this->Customer->get_stats($customer_id);
				$data['customer_total'] = empty($cust_stats) ? 0 : $cust_stats->total;
			}

			if($this->session->userdata('sales_mode') != 'return')
			{
				if(empty($this->session->userdata('applied_credit_note')))
				{
					if(isset($this->check_my_credit($customer_id)[0]))
					{
						$data['customer_credit_note'] = $this->check_my_credit($customer_id)[0]['cn_number'];
					}
				}
			}


			// $data['customer_info'] = implode("\n", array(
			// $data['customer'],
			// $data['customer_gstin'],
			// $data['customer_address'],
			// $data['customer_location'],
			// $data['customer_account_number']
			// ));

			$data['customer_billing_info'] = array(
				'name' => '<b>'.$customer_info->first_name.' '.$customer_info->last_name.'</b>',
				// 'address' => $customer_info->address_1.', '.$customer_info->address_2.', '.$customer_info->city.', '.$customer_info->state.' ('.$customer_info->zip.')',
				'phone' => $customer_info->phone_number,
				'gstin' => $customer_info->gstin
			);
		}

		return $data;
	}

	// public function detect_bogo($cart_data)
	// {
	// 	$main_response = array();
	// 	$response = array();
	// 	$discount_val = 0;
	// 	$bogo_data = $this->db->where('status', 'checked')->get('special_bogo')->result_array();

	// 	foreach($cart_data as $row)
	// 	{
	// 		$response = array();
	// 		$item_id = $row['item_id'];
	// 		$quantity = $row['quantity'];
	// 		$retail_fp = $row['price'];

	// 		foreach($bogo_data as $row)
	// 		{
	// 			$bogo_fp = $row['bogo_fp'];
	// 			if($quantity > 1 && $retail_fp == $bogo_fp)
	// 			{
	// 				$item_info = $this->Item->get_info($item_id);
	// 				if($item_info->category == $row['category'] && $item_info->subcategory == $row['subcategory'] && $item_info->brand == $row['brand'])
	// 				{
	// 					$discount_val += $row['bogo_val'] * round( ($quantity/$row['bogo_count']), 0, PHP_ROUND_HALF_DOWN );
	// 				}
	// 			}	
	// 		}
	// 	}

	// 	if($discount_val > 0)
	// 	{
	// 		$discount_val *= -1;
	// 		$this->sale_lib->set_bogo_value($discount_val);
	// 		return TRUE;
	// 	}
	// 	return FALSE;
	// }

	public function detect_bogo1() // HOTFIX
	{
		$cart_data = $this->session->userdata('sales_cart');
		$discount_val = 0;
		$addUp = array(); 

		foreach($cart_data as $row)
		{
			$item_info = $this->Item->get_info($row['item_id']);
			if($item_info->brand == 'WS')
			{
				$addUp[number_format($row['price'])] += $row['quantity'];
			}
		}

		foreach($addUp as $key=>$value)
		{
			$loop = ( (2 * $key * round( ($value/2), 0, PHP_ROUND_HALF_DOWN )) * 0.3 );
			$discount_val += $loop;
		}

		if($discount_val > 0)
		{
			$discount_val *= -1;
			$this->sale_lib->set_bogo_value($discount_val);
			return TRUE;
		}
		return FALSE;
	}

	public function get_offer_stats($customer_id)
	{
		if($this->Sale->check_my_voucher($customer_id))
		{
			$cart_data = $this->session->userdata('sales_cart');
			$calculation = 0;
			$offer_subcategories = ["MEN'S WOOLLEN", "WOMEN'S WOOLLEN", "KID'S WOOLLEN", "UNISEX WOOLLEN", "MEN'S BLAZER", "MEN'S JACKET COLLAR", "MEN'S WAISTCOAT", "MEN'S SWEATSHIRT", "MEN'S SCARF", "WOMEN'S JACKET"];
			/*
			MEN'S BLAZER
			MEN'S JACKET COLLAR
			MEN'S WAISTCOAT
			MEN'S SWEATSHIRT
			MEN'S WOOLLEN
			MEN'S SCARF
			WOMEN'S WOOLLEN
			WOMEN'S JACKET
			*/
			foreach($cart_data as $items)
			{
				if(in_array($this->db->where('item_id', $items['item_id'])->get('items')->row()->subcategory, $offer_subcategories))
				{
					$calculation += $items['discounted_total'];
				}
			}

			// $voucher_val *= 0.1;
			if($calculation > 0)
			{
				$voucher_discount = 0.1; //10 percent discount
				$scale_value = 2;
				$voucher_val = bcmul($calculation, $voucher_discount, $scale_value);
				

				return array(
					'status' => TRUE,
					'voucher_id' => 3,
					'voucher_code' => 'WINTER10',
					'voucher_value' => $voucher_val
				);
			}
			else
			{
				return array(
					'status' => FALSE,
				);
			}
		}
		else
		{
			return array(
				'status' => FALSE,
			);
		}
	}

	public function random_code($limit)
	{
		return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	public function get_cart_total()
	{
		$cart_total = 0;
		$sales_cart = $this->session->userdata('sales_cart');

		$offer_categories = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];

		foreach($sales_cart as $row)
		{
			if(in_array($this->Item->get_info($row['item_id'])->category, $offer_categories))
			{
				$cart_total += $row['discounted_total'];
			}
		}

		return $cart_total;
	}

	// GIFT VOUCHER FUNCTIONS #start
	public function populate_gift_vc()
	{

	}

	public function redeem_gift_vc()
	{
		
	}
	// GIFT VOUCHER FUNCTIONS #end


	// -------------------------------------------------------------------------------------

	// REWARD VOUCHER FUNCTIONS #start
	public function check_reward_vc($id)
	{
		$vc_data = $this->db->where('id', $id)->get('special_vc')->row();

		$cart_total = $this->get_cart_total();
		$vc_threshold = $vc_data->earn_threshold;
		
		return ($cart_total >= $vc_threshold) ? TRUE : FALSE; // EARN THRESHOLD CHECK
	}

	public function store_reward_vc($voucher_id, $sale_id)
	{
		$person_id = $this->session->userdata('sales_customer');
		$earned_vc_code = $this->random_code(8); // random 8 digit code

		$data = array(
			'person_id' => $person_id,
			'voucher_id' => $voucher_id,
			'voucher_code' => $earned_vc_code,
			'generate_sale_id' => $sale_id
		);

		return ($this->db->insert('special_vc_out', $data)) ? TRUE : FALSE;
	}

	public function try_voucher_code()
	{
		$vc_code = $this->input->post('vc_code'); // vc_code input by cashier from customer's bill
		
		$available_vc = $this->db->where(array(
				'voucher_code' => $vc_code,
				'person_id' => $this->session->userdata('sales_customer')
			))->get('special_vc_out')->row();

		if(!empty($available_vc)) // IF VC CODE CORRECT
		{
			$voucher_id = $available_vc->voucher_id;
			$vc_data = $this->db->where('id', $voucher_id)->get('special_vc')->row();

			$cart_total = $this->get_cart_total();
			$vc_threshold = $vc_data->redeem_threshold;

			if($cart_total >= $vc_threshold) // REDEEM THRESHOLD CHECK
			{
				$this->sale_lib->set_redeem_voucher_id($voucher_id);
				$this->sale_lib->add_payment('Reward Voucher', $vc_data->vc_value);
				echo 'Voucher Code Applied! - '.$available_vc->voucher_code;
				
			}
			else
			{
				$this->sale_lib->set_redeem_voucher_id(0);
				echo 'Minimum Purchase: '.to_currency($vc_data->redeem_threshold);
			}
			
		}
		else
		{
			$this->sale_lib->set_redeem_voucher_id(-1);
			echo 'Invalid Voucher Code...';
		}
	}

	public function redeem_reward_vc($voucher_id, $sale_id)
	{
		$person_id = $this->session->userdata('sales_customer');
		$earned_ = $this->random_code(8); // random 8 digit code

		$data = array(
			'person_id' => $person_id,
			'voucher_id' => $voucher_id
		);

		$data2 = array(
			'redeem_sale_id' => $sale_id,
			'redeemed_at' => date('Y-m-d H:i:s')
		);

		return ($this->db->where($data)->update('special_vc_out', $data2)) ? TRUE : FALSE;
	}
	// REWARD VOUCHER FUNCTIONS #end

	// --------------------------------------------------------------------------------------

	private function _load_sale_data($sale_id)
	{
		$this->sale_lib->clear_all();
		$this->sale_lib->reset_cash_flags();
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->session->set_userdata('billtype', $sale_info['bill_type']);
		$this->sale_lib->copy_entire_sale($sale_id);
		$data = array();
		$data['cart'] = $this->sale_lib->get_cart();
		$data['payments'] = $this->sale_lib->get_payments();
		$data['selected_payment_type'] = $this->sale_lib->get_payment_type();

		$data['taxes'] = $this->sale_lib->get_taxes();
		$data['discount'] = $this->sale_lib->get_discount();
		$data['transaction_time'] = date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), strtotime($sale_info['sale_time']));
		$data['transaction_date'] = date($this->config->item('dateformat'), strtotime($sale_info['sale_time']));
		$data['show_stock_locations'] = $this->Stock_location->show_locations('sales');


		// Returns 'subtotal', 'total', 'cash_total', 'payment_total', 'amount_due', 'cash_amount_due', 'payments_cover_total'
		$totals = $this->sale_lib->get_totals();
		$data['subtotal'] = $totals['subtotal'];
		$data['total'] = $totals['total'];
		$data['payments_total'] = $totals['payment_total'];
		$data['payments_cover_total'] = $totals['payments_cover_total'];
		$data['cash_rounding'] = $this->session->userdata('cash_rounding');
		$data['prediscount_subtotal'] = $totals['prediscount_subtotal'];
		$data['cash_total'] = $totals['cash_total'];
		$data['non_cash_total'] = $totals['total'];
		$data['cash_amount_due'] = $totals['cash_amount_due'];
		$data['non_cash_amount_due'] = $totals['amount_due'];

		if($this->session->userdata('cash_rounding'))
		{
			$data['total'] = $totals['cash_total'];
			$data['amount_due'] = $totals['cash_amount_due'];
		}
		else
		{
			$data['total'] = $totals['total'];
			$data['amount_due'] = $totals['amount_due'];
		}
		$data['amount_change'] = $data['amount_due'] * -1;

		$employee_info = $this->Employee->get_info($this->sale_lib->get_employee());
		$data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name[0];
		$this->_load_customer_data($this->sale_lib->get_customer(), $data);

		$data['sale_id_num'] = $sale_id;
		$data['sale_id'] = 'POS ' . $sale_id;
		$data['comments'] = $sale_info['comment'];
		$data['invoice_number'] = $sale_info['invoice_number'];
		$data['tally_number'] = $sale_info['tally_number'];
		$data['cashier_name'] = $this->Sale->get_cashier_detail($sale_info['cashier_id'], 'name');
		$data['cashier_sale_code'] = $this->Sale->get_cashier_detail($sale_info['cashier_id'], 'id');
		$data['quote_number'] = $sale_info['quote_number'];
		$data['sale_status'] = $sale_info['sale_status'];
		$data['bill_type'] = $sale_info['bill_type'];
		$data['company_info'] = implode("\n", array(
			$this->config->item('address'),
			$this->config->item('phone'),
			$this->config->item('account_number')
		));
		$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['sale_id']);
		$data['print_after_sale'] = FALSE;
		$data['price_work_orders'] = FALSE;

		$totals = $this->sale_lib->get_totals();
		$data['total_units'] = $totals['total_units'];

		if($this->sale_lib->get_mode() == 'sale_invoice')
		{
			$data['mode_label'] = $this->lang->line('sales_invoice');
			// $data['customer_required'] = $this->lang->line('sales_customer_required');
		}
		elseif($this->sale_lib->get_mode() == 'sale_quote')
		{
			$data['mode_label'] = $this->lang->line('sales_quote');
			// $data['customer_required'] = $this->lang->line('sales_customer_required');
		}
		elseif($this->sale_lib->get_mode() == 'sale_work_order')
		{
			$data['mode_label'] = $this->lang->line('sales_work_order');
			// $data['customer_required'] = $this->lang->line('sales_customer_required');
		}
		elseif($this->sale_lib->get_mode() == 'return')
		{
			$data['mode_label'] = $this->lang->line('sales_return');
			// $data['customer_required'] = $this->lang->line('sales_customer_optional');
		}
		else
		{
			$data['mode_label'] = $this->lang->line('sales_receipt');
			// $data['customer_required'] = $this->lang->line('sales_customer_optional');
		}

		return $this->xss_clean($data);
	}

	public function lock_bill()
	{
		$customer_id = $this->session->userdata('sales_customer');
		if($this->session->userdata('sales_mode') == 'return')
		{
			$this->sale_lib->lock_bill();
			echo TRUE;
		}
		else
		{
			$purchase_limits = $this->check_purchase_limits($customer_id);
			if($purchase_limits === TRUE)
			{
				$this->sale_lib->lock_bill();
			}
			echo $purchase_limits;
		}
	}

	private function _reload($data = array())
	{		
		$sale_id = $this->session->userdata('sale_id');
		if($sale_id == '')
		{
			$sale_id = -1;
			$this->session->set_userdata('sale_id', -1);
		}
		$data['cart'] = $this->sale_lib->get_cart();
		$customer_info = $this->_load_customer_data($this->sale_lib->get_customer(), $data, TRUE);
		
		if($this->session->userdata('sales_mode') != 'return') 
		{
			// code for earning Rs.500 voucher
			$voucher_id = 2; // hard-coded for now

			if($this->check_reward_vc($voucher_id))
			{
				$this->sale_lib->set_earned_voucher_id($voucher_id);
			}
		}

		foreach($this->get_custom_fields('billtype', 'custom_fields') as $row)
		{
			$billings[$this->xss_clean($row['varchar_value'])] = $this->xss_clean($row['title']);
		}
		$data['billings'] = $billings;
		$data['selected_bill'] = $this->session->userdata('billtype');

		foreach($this->get_custom_fields('taxtype', 'custom_fields') as $row)
		{
			$custom_taxes[$this->xss_clean($row['varchar_value'])] = $this->xss_clean($row['title']);
		}
		$data['custom_taxes'] = $custom_taxes;
		$data['custom_selected_tax'] = $this->session->userdata('taxtype');

		$franchises = array('' => 'Select Franchise');
		foreach($this->Sale->get_franchises() as $row)
		{
			$franchises[$this->xss_clean($row['int_value'])] = $this->xss_clean($row['title']);
		}
		$data['franchises'] = $franchises;

		$data['modes'] = $this->sale_lib->get_register_mode_options();
		$data['mode'] = $this->sale_lib->get_mode();
		$data['empty_tables'] = $this->sale_lib->get_empty_tables();
		$data['selected_table'] = $this->sale_lib->get_dinner_table();
		$data['stock_locations'] = $this->Stock_location->get_allowed_locations('sales');
		$data['stock_location'] = $this->sale_lib->get_sale_location();
		$data['tax_exclusive_subtotal'] = $this->sale_lib->get_subtotal(TRUE, TRUE);
		$data['taxes'] = $this->sale_lib->get_taxes();
		$data['discount'] = $this->sale_lib->get_discount();
		$data['payments'] = $this->sale_lib->get_payments();
		// sale_type (0=pos, 1=invoice, 2=work order, 3=quote, 4=return)
		$sale_type = $this->sale_lib->get_sale_type();

		// Returns 'subtotal', 'total', 'cash_total', 'payment_total', 'amount_due', 'cash_amount_due', 'payments_cover_total'
		$totals = $this->sale_lib->get_totals();
		$data['item_count'] = $totals['item_count'];
		$data['total_units'] = $totals['total_units'];
		$data['subtotal'] = $totals['subtotal'];
		$data['total'] = $totals['total'];
		$data['payments_total'] = $totals['payment_total'];
		$data['payments_cover_total'] = $totals['payments_cover_total'];
		$data['cash_rounding'] = $this->session->userdata('cash_rounding');
		$data['prediscount_subtotal'] = $totals['prediscount_subtotal'];
		$data['cash_total'] = $totals['cash_total'];
		$data['non_cash_total'] = $totals['total'];
		$data['cash_amount_due'] = $totals['cash_amount_due'];
		$data['non_cash_amount_due'] = $totals['amount_due'];

		if($data['cash_rounding'])
		{
			$data['total'] = $totals['cash_total'];
			$data['amount_due'] = $totals['cash_amount_due'];
		}
		else
		{
			$data['total'] = $totals['total'];
			$data['amount_due'] = $totals['amount_due'];
		}
		$data['amount_change'] = $data['amount_due'] * -1;

		$data['comment'] = $this->sale_lib->get_comment();
		$data['email_receipt'] = $this->sale_lib->is_email_receipt();
		$data['selected_payment_type'] = $this->sale_lib->get_payment_type();
		if($customer_info && $this->config->item('customer_reward_enable') == TRUE)
		{
			$data['payment_options'] = $this->Sale->get_payment_options(TRUE, TRUE);
		}
		else
		{
			$data['payment_options'] = $this->Sale->get_payment_options();
		}

		$cashiers = array('' => 'Select Cashier');
		foreach($this->Sale->get_cashiers() as $key=>$value)
		{
			$cashiers[$this->xss_clean($key)] = $this->xss_clean($value);
		}
		$data['cashiers'] = $cashiers;
		$data['cashier'] = $this->session->userdata('cashier_id');

		$data['items_module_allowed'] = $this->Employee->has_grant('items', $this->Employee->get_logged_in_employee_info()->person_id);

		$invoice_format = $this->config->item('sales_invoice_format');
		$data['invoice_format'] = $invoice_format;

		$this->set_invoice_number($invoice_format);
		$data['invoice_number'] = $invoice_format;
		$data['new_invoice_number'] = $this->Sale->invoice_factory();

		$data['invoice_number_enabled'] = $this->sale_lib->is_invoice_mode();
		$data['print_after_sale'] = $this->sale_lib->is_print_after_sale();
		$data['price_work_orders'] = $this->sale_lib->is_price_work_orders();

		$data['pos_mode'] = $data['mode'] == 'sale' || $data['mode'] == 'return';

		$data['quote_number'] = $this->sale_lib->get_quote_number();
		$data['work_order_number'] = $this->sale_lib->get_work_order_number();

		if($this->sale_lib->get_mode() == 'sale_invoice')
		{
			$data['mode_label'] = $this->lang->line('sales_invoice');
			// $data['customer_required'] = $this->lang->line('sales_customer_required');
		}
		elseif($this->sale_lib->get_mode() == 'sale_quote')
		{
			$data['mode_label'] = $this->lang->line('sales_quote');
			// $data['customer_required'] = $this->lang->line('sales_customer_required');
		}
		elseif($this->sale_lib->get_mode() == 'sale_work_order')
		{
			$data['mode_label'] = $this->lang->line('sales_work_order');
			// $data['customer_required'] = $this->lang->line('sales_customer_required');
		}
		elseif($this->sale_lib->get_mode() == 'return')
		{
			$data['mode_label'] = $this->lang->line('sales_return');
			$data['selected_payment_type'] = 'Credit Note';
			$return_payment_options = array(
				$this->lang->line('sales_credit_note') => $this->lang->line('sales_credit_note')
			);
			$data['payment_options'] = $return_payment_options;
			// $data['customer_required'] = $this->lang->line('sales_customer_optional');
		}
		else
		{
			$data['mode_label'] = $this->lang->line('sales_receipt');
			// $data['customer_required'] = $this->lang->line('sales_customer_optional');
		}

		$data = $this->xss_clean($data);

		$this->load->view("sales/register", $data);
	}

	public function receipt($sale_id)
	{
		$data = $this->_load_sale_data($sale_id);
		$this->load->view('sales/receipt', $data);
		$this->sale_lib->clear_all();
	}

	public function invoice($sale_id)
	{
		$data = $this->_load_sale_data($sale_id);
		$this->load->view('sales/invoice', $data);
		$this->sale_lib->clear_all();
	}

	public function credit_note($sale_id)
	{
		$data = $this->_load_sale_data($sale_id);
		
		$result = $this->Sale->get_credit_note_details($sale_id);
		$data['ref_invoice_number'] = $result['ref_invoice_number'];
		$data['credit_note_number'] = $result['credit_note_number'];
		$this->load->view('sales/credit_note', $data);
		$this->sale_lib->clear_all();
	}

	public function edit($sale_id)
	{
		$data = array();

		$data['employees'] = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			foreach(get_object_vars($employee) as $property => $value)
			{
				$employee->$property = $this->xss_clean($value);
			}

			$data['employees'][$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
		}

		$sale_info = $this->xss_clean($this->Sale->get_info($sale_id)->row_array());
		$data['selected_customer_name'] = $sale_info['customer_name'];
		$data['selected_customer_id'] = $sale_info['customer_id'];
		$data['sale_info'] = $sale_info;

		$data['payments'] = array();
		foreach($this->Sale->get_sale_payments($sale_id)->result() as $payment)
		{
			foreach(get_object_vars($payment) as $property => $value)
			{
				$payment->$property = $this->xss_clean($value);
			}
			$data['payments'][] = $payment;
		}

		// don't allow gift card to be a payment option in a sale transaction edit because it's a complex change
		$data['payment_options'] = $this->xss_clean($this->Sale->get_payment_options(FALSE));
		$this->load->view('sales/form', $data);
	}

	public function delete($sale_id = -1, $update_inventory = TRUE)
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$has_grant = $this->Employee->has_grant('sales_delete', $employee_id);

		if(!$has_grant)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_not_authorized')));
		}
		else
		{
			$sale_ids = $sale_id == -1 ? $this->input->post('ids') : array($sale_id);

			if($this->Sale->delete_list($sale_ids, $employee_id, $update_inventory))
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('sales_successfully_deleted') . ' ' .
					count($sale_ids) . ' ' . $this->lang->line('sales_one_or_multiple'), 'ids' => $sale_ids));
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_unsuccessfully_deleted')));
			}
		}
	}

	public function restore($sale_id = -1, $update_inventory = TRUE)
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$has_grant = $this->Employee->has_grant('sales_delete', $employee_id);

		if(!$has_grant)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_not_authorized')));
		}
		else
		{
			$sale_ids = $sale_id == -1 ? $this->input->post('ids') : array($sale_id);

			if($this->Sale->restore_list($sale_ids, $employee_id, $update_inventory))
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('sales_successfully_restored') . ' ' .
					count($sale_ids) . ' ' . $this->lang->line('sales_one_or_multiple'), 'ids' => $sale_ids));
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_unsuccessfully_restored')));
			}
		}
	}

	/**
	 * This saves the sale from the update sale view (sales/form).
	 * It only updates the sales table and payments.
	 * @param int $sale_id
	 */
	public function save($sale_id = -1)
	{
		$newdate = $this->input->post('date');

		$date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $newdate);

		$sale_data = array(
			'sale_time' => $date_formatter->format('Y-m-d H:i:s'),
			'customer_id' => $this->input->post('customer_id') != '' ? $this->input->post('customer_id') : NULL,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'invoice_number' => $this->input->post('invoice_number') != '' ? $this->input->post('invoice_number') : NULL
		);

		// go through all the payment type input from the form, make sure the form matches the name and iterator number
		$payments = array();
		$number_of_payments = $this->input->post('number_of_payments');
		for($i = 0; $i < $number_of_payments; ++$i)
		{
			$payment_amount = $this->input->post('payment_amount_' . $i);
			$payment_type = $this->input->post('payment_type_' . $i);
			// remove any 0 payment if by mistake any was introduced at sale time
			if($payment_amount != 0)
			{
				// search for any payment of the same type that was already added, if that's the case add up the new payment amount
				$key = FALSE;
				if(!empty($payments))
				{
					// search in the multi array the key of the entry containing the current payment_type
					// NOTE: in PHP5.5 the array_map could be replaced by an array_column (DONE)
					$key = array_search($payment_type, array_column($payments, 'payment_type'));
				}

				// if no previous payment is found add a new one
				if($key === FALSE)
				{
					$payments[] = array('payment_type' => $payment_type, 'payment_amount' => $payment_amount);
				}
				else
				{
					// add up the new payment amount to an existing payment type
					$payments[$key]['payment_amount'] += $payment_amount;
				}
			}
		}

		if($this->Sale->update($sale_id, $sale_data, $payments))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('sales_successfully_updated'), 'id' => $sale_id));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('sales_unsuccessfully_updated'), 'id' => $sale_id));
		}
	}

	/**
	 * This is used to cancel a suspended pos sale, quote.
	 * Completed sales (POS Sales or Invoiced Sales) can not be removed from the system
	 * Work orders can be canceled but are not physically removed from the sales history
	 */
	public function cancel()
	{
		$sale_id = $this->sale_lib->get_sale_id();
		if($sale_id != -1 && $sale_id != '')
		{
			$sale_type = $this->sale_lib->get_sale_type();
			if($sale_type == SALE_TYPE_WORK_ORDER)
			{
				$this->Sale->update_sale_status($sale_id, CANCELED);
			}
			else
			{
				$this->Sale->delete($sale_id, FALSE);
				$this->session->set_userdata('sale_id', -1);
			}
		}

		$this->sale_lib->clear_all();
		$this->_reload();
	}

	public function discard_suspended_sale()
	{
		$suspended_id = $this->sale_lib->get_suspended_id();
		$this->sale_lib->clear_all();
		$this->Sale->delete_suspended_sale($suspended_id);
		$this->_reload();
	}

	/**
	 * Suspend the current sale.
	 * If the current sale is already suspended then update the existing suspended sale.
	 * Otherwise create it as a new suspended sale
	 */
	public function suspend()
	{
		$mode = $this->sale_lib->get_mode();
		$sale_id = $this->sale_lib->get_sale_id();
		$dinner_table = $this->sale_lib->get_dinner_table();
		$cart = $this->sale_lib->get_cart();
		$payments = $this->sale_lib->get_payments();
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$customer_id = $this->sale_lib->get_customer();
		$customer_info = $this->Customer->get_info($customer_id);
		$invoice_number = $this->sale_lib->get_invoice_number();
		$cashier_id = $this->session->userdata('cashier_id');
		$work_order_number = $this->sale_lib->get_work_order_number();
		$quote_number = $this->sale_lib->get_quote_number();
		$sale_type = $this->sale_lib->get_sale_type();
		if($sale_type == '')
		{
			$sale_type = SALE_TYPE_POS;
		}
		$comment = $this->sale_lib->get_comment();
		$sale_status = SUSPENDED;

		$data = array();
		$sales_taxes = array();
		if($this->Sale->save($sale_id, $sale_status, $cart, $customer_id, $employee_id, $comment, $invoice_number, $cashier_id, $work_order_number, $quote_number, $sale_type, $payments, $dinner_table, $sales_taxes) == '-1')
		{
			$data['error'] = $this->lang->line('sales_unsuccessfully_suspended_sale');
		}
		else
		{
			$data['success'] = $this->lang->line('sales_successfully_suspended_sale');
		}

		$this->sale_lib->clear_all();
		$this->_reload($data);
	}

	/**
	 * List suspended sales
	 */
	public function suspended()
	{
		$customer_id = $this->sale_lib->get_customer();
		$data = array();
		$data['suspended_sales'] = $this->xss_clean($this->Sale->get_all_suspended($customer_id));
		$data['dinner_table_enable'] = $this->config->item('dinner_table_enable');
		$this->load->view('sales/suspended', $data);
	}

	/*
	 * Unsuspended sales are now left in the tables and are only removed
	 * when they are intentionally cancelled.
	 */
	public function unsuspend()
	{
		$sale_id = $this->input->post('suspended_sale_id');
		$this->sale_lib->clear_all();

		if($sale_id > 0)
		{
			$this->sale_lib->copy_entire_sale($sale_id);
		}

		// Set current register mode to reflect that of unsuspended order type
		$this->change_register_mode($this->sale_lib->get_sale_type());

		$this->_reload();
	}

	public function check_invoice_number()
	{
		$sale_id = $this->input->post('sale_id');
		$invoice_number = $this->input->post('invoice_number');
		$exists = !empty($invoice_number) && $this->Sale->check_invoice_number_exists($invoice_number, $sale_id);
		echo !$exists ? 'true' : 'false';
	}

	public function get_filtered($cart)
	{
		$filtered_cart = array();
		foreach($cart as $id => $item)
		{
			if($item['print_option'] == PRINT_ALL) // always include
			{
				$filtered_cart[$id] = $item;
			}
			elseif($item['print_option'] == PRINT_PRICED && $item['price'] != 0)  // include only if the price is not zero
			{
				$filtered_cart[$id] = $item;
			}
			// print_option 2 is never included
		}

		return $filtered_cart;
	}

	public function check_purchase_limits($customer_id)
  {
		$today = date("Y-m-d");

    $this->db->select('
			sales.sale_id AS sale_id,
			sales.sale_status AS sale_status,
			sales.sale_time AS sale_time,
			sales.customer_id AS customer_id,
			sales_items.item_id AS item_id,
			sales_items.quantity_purchased AS quantity,
    ');
    $this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id');
		$this->db->where('sale_status', 0);
		$this->db->where('customer_id', $customer_id);
    $this->db->where('DATE(sale_time) BETWEEN "'.rawurldecode($today).'" AND "'.rawurldecode($today).'"');
		$dbItems = $this->db->get()->result_array();

		foreach($dbItems as $row)
		{
			$perItem1['item_id'] = $row['item_id'];
			$perItem1['category'] = $this->Item->get_info($row['item_id'])->category;
			$perItem1['quantity'] = $row['quantity'];
			$dbCart[] = $perItem1;
		}

		foreach($this->session->userdata('sales_cart') as $row)
		{
			$perItem2['item_id'] = $row['item_id'];
			$perItem2['category'] = $this->Item->get_info($row['item_id'])->category;
			$perItem2['quantity'] = $row['quantity'];
			$nowCart[] = $perItem2;
		}

		$fullCart = (!empty($dbCart)) ? array_merge_recursive($dbCart, $nowCart) : $nowCart;

		$catCount = array(); 
		foreach($fullCart as $row){    
			$catCount[$row['category']] += $row['quantity'];
		} 
		// echo json_encode($catCount);
		
		$ruleset = $this->db->where('status', 'checked')->get('purchase_limiter')->result_array();
		
		foreach($catCount as $key=>$value)
		{
			foreach($ruleset as $row)
			{
				if($key == $row['mci_value'])
				{
					if($value > $row['quantity'])
					{
						$disputed['mci'] = $row['mci_value'];
						$disputed['exceed_by'] = $value - $row['quantity'];
					}
				}
			}
		}

		return (empty($disputed)) ? TRUE : json_encode($disputed);
	}
	
}

?>