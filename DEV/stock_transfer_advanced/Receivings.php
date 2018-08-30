<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Receivings extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('receivings');

		$this->load->library('receiving_lib');
		$this->load->library('barcode_lib');
	}

	public function index()
	{
		$this->_reload();
	}

	public function item_search()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->get('term'), array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($this->input->get('term')));

		$suggestions = $this->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function stock_item_search()
	{
		$suggestions = $this->Item->get_stock_search_suggestions($this->input->get('term'), array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($this->input->get('term')));

		$suggestions = $this->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function select_supplier()
	{
		$supplier_id = $this->input->post('supplier');
		if($this->Supplier->exists($supplier_id))
		{
			$this->receiving_lib->set_supplier($supplier_id);
		}

		$this->_reload();
	}

	public function change_mode()
	{
		$stock_destination = $this->input->post('stock_destination');
		$stock_source = $this->input->post('stock_source');

		if((!$stock_source || $stock_source == $this->receiving_lib->get_stock_source()) &&
			(!$stock_destination || $stock_destination == $this->receiving_lib->get_stock_destination()))
		{
			$this->receiving_lib->clear_reference();
			$mode = $this->input->post('mode');
			$this->receiving_lib->set_mode($mode);
		}
		elseif($this->Stock_location->is_allowed_location($stock_source, 'receivings'))
		{
			$this->receiving_lib->set_stock_source($stock_source);
			$this->receiving_lib->set_stock_destination($stock_destination);
		}

		$this->_reload();
	}

	public function set_comment()
	{
		$this->receiving_lib->set_comment($this->input->post('comment'));
	}

	public function set_print_after_sale()
	{
		$this->receiving_lib->set_print_after_sale($this->input->post('recv_print_after_sale'));
	}
	
	public function set_reference()
	{
		$this->receiving_lib->set_reference($this->input->post('recv_reference'));
	}
	
	public function add()
	{
		$data = array();

		$mode = $this->receiving_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post('item');
		$this->barcode_lib->parse_barcode_fields($quantity, $item_id_or_number_or_item_kit_or_receipt);
		$quantity = ($mode == 'receive' || $mode == 'requisition') ? $quantity : -$quantity;
		$item_location = $this->receiving_lib->get_stock_source();

		if($mode == 'return' && $this->Receiving->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->receiving_lib->return_entire_receiving($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->Item_kit->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->receiving_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $item_location);
		}
		elseif(!$this->receiving_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity, $item_location))
		{
			$data['error'] = $this->lang->line('receivings_unable_to_add_item');
		}

		$this->_reload($data);
	}

	public function edit_item($item_id)
	{
		$data = array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'required|callback_numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|callback_numeric');
		$this->form_validation->set_rules('discount', 'lang:items_discount', 'required|callback_numeric');

		$in_stock = $this->input->post('in_stock');
		$description = $this->input->post('description');
		$serialnumber = $this->input->post('serialnumber');
		$price = parse_decimals($this->input->post('price'));
		$quantity = parse_decimals(abs($this->input->post('quantity')));
		$discount = parse_decimals($this->input->post('discount'));
		$item_location = $this->input->post('location');
		if($quantity > $in_stock){
			$quantity = $in_stock;
			$data['msg']= 'Invalid Transfer Quantity!';
		}

		if($this->form_validation->run() != FALSE)
		{
			$this->receiving_lib->edit_item($item_id, $description, $serialnumber, $quantity, $discount, $price);
		}
		else
		{
			$data['error']=$this->lang->line('receivings_error_editing_item');
		}

		$this->_reload($data);
	}
	
	public function edit($receiving_id)
	{
		$data = array();

		$data['suppliers'] = array('' => 'No Supplier');
		foreach($this->Supplier->get_all()->result() as $supplier)
		{
			$data['suppliers'][$supplier->person_id] = $this->xss_clean($supplier->first_name . ' ' . $supplier->last_name);
		}
	
		$data['employees'] = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $this->xss_clean($employee->first_name . ' '. $employee->last_name);
		}
	
		$receiving_info = $this->xss_clean($this->Receiving->get_info($receiving_id)->row_array());
		$data['selected_supplier_name'] = !empty($receiving_info['supplier_id']) ? $receiving_info['company_name'] : '';
		$data['selected_supplier_id'] = $receiving_info['supplier_id'];
		$data['receiving_info'] = $receiving_info;
	
		$this->load->view('receivings/form', $data);
	}

	public function delete_item($item_number)
	{
		$this->receiving_lib->delete_item($item_number);

		$this->_reload();
	}
	
	public function delete($receiving_id = -1, $update_inventory = TRUE) 
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$receiving_ids = $receiving_id == -1 ? $this->input->post('ids') : array($receiving_id);
	
		if($this->Receiving->delete_list($receiving_ids, $employee_id, $update_inventory))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('receivings_successfully_deleted') . ' ' .
							count($receiving_ids) . ' ' . $this->lang->line('receivings_one_or_multiple'), 'ids' => $receiving_ids));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('receivings_cannot_be_deleted')));
		}
	}

	public function remove_supplier()
	{
		$this->receiving_lib->clear_reference();
		$this->receiving_lib->remove_supplier();

		$this->_reload();
	}

	public function st_view()
	{
		$transfers = $this->get_transfers($this->session->userdata('person_id'), 'rows');
		$receivings = array('' => $this->lang->line('items_none'));

		foreach($transfers->result_array() as $row)
		{
			$receivings[$this->xss_clean($row['receiving_id'])] = $this->xss_clean($row['receiving_time']);
		}
		$data['receivings'] = $receivings;

		$this->load->view('receivings/st_view', $data);
	}

	public function st_fetch_instance() // to fetch a particular transfer
	{
		$array = array(
			'receiving_id' => $this->input->post('receiving_id'),
			'processed' => 0
		);
		$this->db->where($array);
		$query = $this->db->get('stock_movement');
		$data['jsondata'] = json_encode($query->result_array());
		$this->load->view('receivings/sub_st_list', $data);
	}

	public function st_process()
	{
		$success = TRUE;
		$receiving_id = trim($this->input->post('receiving_id'));
		$item_id = trim($this->input->post('item_id'));

		$main_array = array(
			'item_id' => $item_id,
			'receiving_id' => $receiving_id
		);
		$this->db->where($main_array);
		$query = $this->db->get('stock_movement');
		if($query->row('processed') != 1) //restrict process to run twice for same row
		{
			$accept = trim($this->input->post('accept'));
			$good = trim($this->input->post('good'));
			$bad = trim($this->input->post('bad'));
			$scrap = trim($this->input->post('scrap'));

			$data = array(
				'receiving_id' => $receiving_id,
				'item_id' => $item_id,
				'accept' => $accept,
				'good' => $good,
				'bad' => $bad,
				'scrap' => $scrap,
			);
			$success &= $this->db->insert('stock_transfers', $data);

			// ----------------------------------------------------

			$owner_id = $this->Receiving->get_recv_stock_owner($receiving_id, 'destination');

			$location_id = $this->Stock_location->get_location_id_2($owner_id);

			// ----------------------------------------------------

			$array = array(
				'item_id' => $item_id,
				'location_id' => $location_id
			);
			$this->db->where($array);
			$query = $this->db->get('item_quantities');
			$prev_qty = $query->row('quantity');
			$new_qty = $prev_qty + $accept;

			$data_array = array(
				'quantity' => $new_qty
			);
			$this->db->where($array);
			$success &= $this->db->update('item_quantities', $data_array);

			// ----------------------------------------------------		
			$data_array = array(
				'processed' => 1
			);
			$this->db->where($main_array);
			$success &= $this->db->update('stock_movement', $data_array);

			echo (($success) ? 'success' : 'failed');
		
		}
		else
		{
			echo 'Already Processed';
		}

	}

	public function st_complete()
	{
		$success = TRUE;
		$recv = $this->input->post('recv'); // receiving id

		$this->db->where('receiving_id', $recv);
		$query = $this->db->get('receivings');
		if($query->row('completed') != 1) //restrict process to run twice for same row
		{
			$array = array(
				'receiving_id' => $recv,
				'processed' => 1
			);
			$this->db->where($array);
			$items = $this->db->get('stock_movement')->result_array();

			foreach($items as $item)
			{
				$array = array(
					'receiving_id' => $recv,
					'item_id' => $item['item_id']
				);
				$this->db->where($array);
				$query = $this->db->get('stock_transfers');
				$good = $query->row('good'); //add this good qty to the source's stock

				// ----------------------------------------------------

				$owner_id = $this->Receiving->get_recv_stock_owner($recv, 'employee_id');

				$source_location_id = $this->Stock_location->get_location_id_2($owner_id);

				// ----------------------------------------------------

				$array = array(
					'item_id' => $item['item_id'],
					'location_id' => $source_location_id
				);
				$this->db->where($array);
				$query = $this->db->get('item_quantities');
				$prev_qty = $query->row('quantity');
				$new_qty = $prev_qty + $good;
		
				$data = array(
					'quantity' => $new_qty
				);

				$this->db->where($array);
				$success &= $this->db->update('item_quantities', $data);
			}

			$this->db->where('receiving_id', $recv);
			$data = array(
				'completed' => 1
			);
			$success &= $this->db->update('receivings', $data);

			echo (($success) ? 'success' : 'failed');
		}
		else
		{
			echo 'Already Processed';
		}

		
	}

	public function complete($owner_id = -1)
	{
		$data = array();
		
		$data['cart'] = $this->receiving_lib->get_cart();
		$data['total'] = $this->receiving_lib->get_total();
		$data['transaction_time'] = date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'));
		$data['mode'] = $this->receiving_lib->get_mode();
		$data['comment'] = $this->receiving_lib->get_comment();
		$data['reference'] = $this->receiving_lib->get_reference();
		$data['payment_type'] = $this->input->post('payment_type');
		$data['show_stock_locations'] = $this->Stock_location->show_locations('receivings');
		$data['stock_location'] = $this->receiving_lib->get_stock_source();
		if($this->input->post('amount_tendered') != NULL)
		{
			$data['amount_tendered'] = $this->input->post('amount_tendered');
			$data['amount_change'] = to_currency($data['amount_tendered'] - $data['total']);
		}
		
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$employee_info = $this->Employee->get_info($employee_id);
		$data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name;

		$supplier_info = '';
		$supplier_id = $this->receiving_lib->get_supplier();
		if($supplier_id != -1)
		{
			$supplier_info = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier_info->company_name;
			$data['first_name'] = $supplier_info->first_name;
			$data['last_name'] = $supplier_info->last_name;
			$data['supplier_email'] = $supplier_info->email;
			$data['supplier_address'] = $supplier_info->address_1;
			if(!empty($supplier_info->zip) or !empty($supplier_info->city))
			{
				$data['supplier_location'] = $supplier_info->zip . ' ' . $supplier_info->city;				
			}
			else
			{
				$data['supplier_location'] = '';
			}
		}

		//SAVE receiving to database (ospos_receivings)
		$data['receiving_id'] = 'RECV ' . $this->Receiving->save($owner_id, $data['cart'], $supplier_id, $employee_id, $data['comment'], $data['reference'], $data['payment_type'], $data['stock_location']);

		$data = $this->xss_clean($data);

		if($data['receiving_id'] == 'RECV -1')
		{
			$data['error_message'] = $this->lang->line('receivings_transaction_failed');
		}
		else
		{
			$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['receiving_id']);				
		}

		$data['print_after_sale'] = $this->receiving_lib->is_print_after_sale();



		$this->load->view("receivings/receipt",$data);

		$this->receiving_lib->clear_all();
	}

	public function requisition_complete()
	{
		if($this->receiving_lib->get_stock_source() != $this->receiving_lib->get_stock_destination()) 
		{
			foreach($this->receiving_lib->get_cart() as $item)
			{
				$this->receiving_lib->delete_item($item['line']);
				$this->receiving_lib->add_item($item['item_id'], $item['quantity'], $this->receiving_lib->get_stock_destination());
				$this->receiving_lib->add_item($item['item_id'], -$item['quantity'], $this->receiving_lib->get_stock_source());
			}
			
			$this->complete($this->input->post('location_owner'));
		}
		else 
		{
			$data['error'] = $this->lang->line('receivings_error_requisition');

			$this->_reload($data);	
		}
	}
	
	public function receipt($receiving_id)
	{
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		$this->receiving_lib->copy_entire_receiving($receiving_id);
		$data['cart'] = $this->receiving_lib->get_cart();
		$data['total'] = $this->receiving_lib->get_total();
		$data['mode'] = $this->receiving_lib->get_mode();
		$data['transaction_time'] = date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), strtotime($receiving_info['receiving_time']));
		$data['show_stock_locations'] = $this->Stock_location->show_locations('receivings');
		$data['payment_type'] = $receiving_info['payment_type'];
		$data['reference'] = $this->receiving_lib->get_reference();
		$data['receiving_id'] = 'RECV ' . $receiving_id;
		$data['barcode'] = $this->barcode_lib->generate_receipt_barcode($data['receiving_id']);
		$employee_info = $this->Employee->get_info($receiving_info['employee_id']);
		$data['employee'] = $employee_info->first_name . ' ' . $employee_info->last_name;

		$supplier_id = $this->receiving_lib->get_supplier();
		if($supplier_id != -1)
		{
			$supplier_info = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier_info->company_name;
			$data['first_name'] = $supplier_info->first_name;
			$data['last_name'] = $supplier_info->last_name;
			$data['supplier_email'] = $supplier_info->email;
			$data['supplier_address'] = $supplier_info->address_1;
			if(!empty($supplier_info->zip) or !empty($supplier_info->city))
			{
				$data['supplier_location'] = $supplier_info->zip . ' ' . $supplier_info->city;				
			}
			else
			{
				$data['supplier_location'] = '';
			}
		}

		$data['print_after_sale'] = FALSE;

		$data = $this->xss_clean($data);
		
		$this->load->view("receivings/receipt", $data);

		$this->receiving_lib->clear_all();
	}

	public function get_transfers($id, $type)
	{
		$this->db->from('receivings');
		$array = array(
			'destination' => $id,
			'completed' => 0
		);
		$this->db->where($array);
		if($type == 'count')
		{
			return $this->db->count_all_results();
		}
		else if($type == 'rows')
		{
			return $this->db->get();
		}
		
	}

	private function _reload($data = array())
	{
		$data['transfers'] = $this->get_transfers($this->session->userdata('person_id'), 'count');
		$data['cart'] = $this->receiving_lib->get_cart();
		$data['modes'] = array('receive' => $this->lang->line('receivings_receiving'), 'return' => $this->lang->line('receivings_return'));
		$data['mode'] = $this->receiving_lib->get_mode();
		$data['stock_locations'] = $this->Stock_location->get_allowed_locations('receivings');
		$data['show_stock_locations'] = count($data['stock_locations']) > 1;
		if($data['show_stock_locations']) 
		{
			$data['modes']['requisition'] = $this->lang->line('receivings_requisition');
			$data['stock_source'] = $this->receiving_lib->get_stock_source();
			$data['stock_destination'] = $this->receiving_lib->get_stock_destination();
		}

		$data['total'] = $this->receiving_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_grant('items', $this->Employee->get_logged_in_employee_info()->person_id);
		$data['comment'] = $this->receiving_lib->get_comment();
		$data['reference'] = $this->receiving_lib->get_reference();
		$data['payment_options'] = $this->Receiving->get_payment_options();

		// $supplier_id = $this->receiving_lib->get_supplier();
		// $supplier_info = '';
		// if($supplier_id != -1)
		// {
		// 	$supplier_info = $this->Supplier->get_info($supplier_id);
		// 	$data['supplier'] = $supplier_info->company_name;
		// 	$data['first_name'] = $supplier_info->first_name;
		// 	$data['last_name'] = $supplier_info->last_name;
		// 	$data['supplier_email'] = $supplier_info->email;
		// 	$data['supplier_address'] = $supplier_info->address_1;
		// 	if(!empty($supplier_info->zip) or !empty($supplier_info->city))
		// 	{
		// 		$data['supplier_location'] = $supplier_info->zip . ' ' . $supplier_info->city;				
		// 	}
		// 	else
		// 	{
		// 		$data['supplier_location'] = '';
		// 	}
		// }
		
		$data['print_after_sale'] = $this->receiving_lib->is_print_after_sale();

		$data = $this->xss_clean($data);

		$this->load->view("receivings/receiving", $data);
	}
	
	public function save($receiving_id = -1)
	{
		$newdate = $this->input->post('date');
		
		$date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $newdate);

		$receiving_data = array(
			'receiving_time' => $date_formatter->format('Y-m-d H:i:s'),
			'supplier_id' => $this->input->post('supplier_id') ? $this->input->post('supplier_id') : NULL,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'reference' => $this->input->post('reference') != '' ? $this->input->post('reference') : NULL
		);
	
		if($this->Receiving->update($receiving_data, $receiving_id))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('receivings_successfully_updated'), 'id' => $receiving_id));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('receivings_unsuccessfully_updated'), 'id' => $receiving_id));
		}
	}

	public function cancel_receiving()
	{
		$this->receiving_lib->clear_all();

		$this->_reload();
	}
}
?>
