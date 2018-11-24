<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Offers extends Secure_Controller
{
  public function __construct()
	{
		parent::__construct('offers');
	}

	public function index()
	{
		$data['plans'] = $this->Pricing->get_core_plans();
		$data['mci_data'] = $this->Item->get_mci_data('all');
		$this->load->view('offers/dashboard', $data);
	}

	// --------------------------------- TESTING FUNCTIONS --------------------------------------

	// public function test($item_id)
	// {
	// 	// echo $this->Pricing->check_active_offers($item_id);
	// 	foreach($this->Pricing->check_active_offers($item_id) as $row)
	// 	{
	// 		echo $row."<br>";
	// 	}
	// }

	// public function make_barcode_list()
	// {
	// 	$this->load->view('offers/modals/form_excel_barcodes');
	// }

	// public function do_make_barcode_list()
	// {
	// 	if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
	// 	{
	// 		echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
	// 	}
	// 	else
	// 	{
	// 		if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
	// 		{
	// 			// Skip the first row as it's the table description
	// 			fgetcsv($handle);
	// 			$i = 1;

	// 			$failCodes = array();
	// 			$barcodes = array();

	// 			while(($data = fgetcsv($handle)) !== FALSE)
	// 			{
	// 				// XSS file data sanity check
	// 				$data = $this->xss_clean($data);
	// 				$barcodes[] = $data[0]; //barcode

	// 				++$i;

	// 			} // while loop ends here
	// 			$this->session->set_userdata('barcode_list', $barcodes);

	// 			if(count($failCodes) > 0)
	// 			{
	// 				$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

	// 				echo json_encode(array('success' => FALSE, 'message' => $message));
	// 			}
	// 			else
	// 			{
	// 				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
	// 			}
	// 		}
	// 		else
	// 		{
	// 			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
	// 		}
	// 	}
	// }

	// --------------------------------- DYNAMIC PRICING --------------------------------------

	public function view_basic()
	{
		$mci_data = $this->Item->get_mci_data('all');
		$categories = array('' => $this->lang->line('items_none'));
		foreach($mci_data['categories'] as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['categories'] = $categories;

		$subcategories = array('' => $this->lang->line('items_none'));
		foreach($mci_data['subcategories'] as $row)
		{
			$subcategories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['subcategories'] = $subcategories;

		$brands = array('' => $this->lang->line('items_none'));
		foreach($mci_data['brands'] as $row)
		{
			$brands[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['brands'] = $brands;

		foreach($this->Pricing->get_active_shops(array('shop', 'dbf', 'hub')) as $row)
		{
			$active_shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
		}
		$data['active_shops'] = $active_shops;

		$data['plans'] = $this->Pricing->get_core_plans();
		$this->load->view('offers/modals/view_basic_form', $data);
	}

	public function save_basic()
	{
		$response = array();
		$valid_date_range = 0;

		$locations = $this->input->post('locations');
		$plan = $this->input->post('plan');
		$pointer = ($plan == 'mixed' || $plan == 'mixed2') ? json_encode($this->input->post('pointer')) : trim($this->input->post('pointer'));
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');

		if(strtotime(date("Y-m-d H:i:s")) <= strtotime($start_time) && strtotime($start_time) < strtotime($end_time))
		{
			$valid_date_range = 1;
		}

		$this->db->where('locations', $locations);
		$this->db->where('pointer', $pointer);
		$offer_count = $this->db->count_all_results('special_prices');

		if($offer_count == 0)
		{
			if($valid_date_range == 1)
			{
				$data = array(
					'plan' => $plan,
					'locations' => $locations,
					'pointer' => $pointer,
					'price' => $this->input->post('price'),
					'discount' => $this->input->post('discount'),
					'start_time' => $start_time,
					'end_time' => $end_time
				); 
			
				$this->db->insert('special_prices', $data);
				$response['type'] = "success";
				$response['message'] = "Created Successfully";
			}
			else
			{
				$response['type'] = "error";
				$response['message'] = "Invalid Date Range";
			}
		}
		else
		{
			$response['type'] = 'update';
			$this->db->where('locations', $locations);
			$this->db->where('pointer', $pointer);
			$response['offer_id'] = $this->db->get('special_prices')->row()->id;
		}
		echo json_encode($response);
	}

	public function update_basic()
	{
		$valid_date_range = 0;

		$id = $this->input->post('id');
		// $locations = $this->input->post('locations');
		// $plan = $this->input->post('plan');
		// $pointer = ($plan == 'mixed' || $plan == 'mixed2') ? json_encode($this->input->post('pointer')) : trim($this->input->post('pointer'));
		$start_time = $this->input->post('start_time');
		$end_time = $this->input->post('end_time');

		if(strtotime(date("Y-m-d H:i:s")) <= strtotime($start_time) && strtotime($start_time) < strtotime($end_time))
		{
			$valid_date_range = 1;
		}

		if($valid_date_range == 1)
		{
			$data = array(
				// 'plan' => $plan,
				// 'locations' => $locations,
				// 'pointer' => $pointer,
				'price' => $this->input->post('price'),
				'discount' => $this->input->post('discount'),
				'start_time' => $start_time,
				'end_time' => $end_time
			); 
		
			$this->db->where('id', $id);
			$this->db->update('special_prices', $data);
			echo "Updated Successfully";
		}
		else
		{
			echo "Invalid Date Range";
		}
	}

	public function delete_basic()
	{
		$id = $this->input->post('id');
		$this->db->delete('special_prices', array('id' => $id));
		echo "Deleted Successfully";
	}

	public function offer_toggle()
	{
		$status = ($this->input->post('status') == 'true') ? 'checked' : '';
		$data = array(
			'status' => $status
		);
		$this->db->where('id', $this->input->post('id'));
		echo ($this->db->update('special_prices', $data)) ? 'success' : 'failed';
	}

	public function get_dynamic_prices()
	{
		$data['dynamic_prices'] = $this->db->where('plan', $this->input->post('plan'))->get('special_prices')->result_array();

		$this->load->view('offers/sublists/dynamic_prices', $data);
	}

	// --------------------------------- GIFT VOUCHERS --------------------------------------

	public function view_vc($id = -1)
	{
		$mci_data = $this->Item->get_mci_data('all');
		$categories = array('' => $this->lang->line('items_none'));
		foreach($mci_data['categories'] as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['categories'] = $categories;
		$this->load->view('offers/modals/view_vc_form', $data);
	}

	public function save_vc()
	{
		$customers = $this->db->get('people')->result_array();

		foreach($customers as $row)
		{
			$phone_number = $row['phone_number'];
			if(is_numeric($phone_number) && strlen($phone_number) == 10)
			{
				$data = array(
					'voucher_id' => 1,
					'customer_id' => $this->db->where('phone_number', $phone_number)->get('people')->row()->person_id,
					'phone' => $phone_number
				);
				$this->db->insert('special_vc_out', $data);
			}
		}
	}

	public function get_vc_out($id)
	{	
		$data['vc_list'] = $this->db->where('voucher_id', $id)->get('special_vc_out')->result_array();
		$this->load->view('offers/sublists/vc_list', $data);
	}

	public function get_vc_redeemed($id)
	{	
		$data['vc_list'] = $this->db->where(array('voucher_id' => $id, 'redeemed' => 1))->get('special_vc_out')->result_array();
		$this->load->view('offers/sublists/vc_list', $data);
	}

	public function get_vc_details($id)
	{

	}

	public function vc_toggle()
  {
    $status = ($this->input->post('status') == 'true') ? 'checked' : '';
    $data = array(
      'active' => $status
    );
    $this->db->where('id', $this->input->post('id'));
    echo ($this->db->update('special_vc', $data)) ? 'success' : 'failed';
	}
	
	// --------------------------------- ITEMS CLUB --------------------------------------

	public function save_bogo()
	{
		$id = $this->input->post('id');
		$category = $this->input->post('category');
		$subcategory = $this->input->post('subcategory');
		$brand = $this->input->post('brand');
		$bogo_fp = $this->input->post('bogo_fp');
		$bogo_val = $this->input->post('bogo_val');

		$insert_data = array(
			'category' => $category,
			'subcategory' => $subcategory,
			'brand' => $brand,
			'bogo_fp' => $bogo_fp,
			'bogo_val' => $bogo_val
		);

		$update_data = array(
			'bogo_fp' => $bogo_fp,
			'bogo_val' => $bogo_val
		);

		if(!empty($id))
		{
			$this->db->where('id', $id)->update('special_bogo', $update_data);
			echo json_encode($update_data);
		}
		else
		{
			$this->db->insert('special_bogo', $insert_data);
			echo json_encode($insert_data);
		}
	}

	public function edit_bogo($id)
	{
		$data['bogo_data'] = $this->db->where('id', $id)->get('special_bogo')->row();
		$this->load->view('offers/modals/edit_bogo', $data);
	}

	public function bogo_toggle()
	{
		$status = ($this->input->post('status') == 'true') ? 'checked' : '';
		$data = array(
			'status' => $status
		);
		$this->db->where('id', $this->input->post('id'));
		echo ($this->db->update('special_bogo', $data)) ? 'success' : 'failed';
	}

	public function active_bogo_window()
	{
		$this->load->view('offers/sublists/active_bogo_window');
	}

	// --------------------------------- PURCHASE LIMITS --------------------------------------

	public function save_plimit()
	{
		$id = $this->input->post('id');
		$mci_value = $this->input->post('mci_value');
		$quantity = $this->input->post('quantity');

		$insert_data = array(
			'mci_value' => $mci_value,
			'quantity' => $quantity
		);

		$update_data = array(
			'quantity' => $quantity
		);

		if(!empty($id))
		{
			$this->db->where('id', $id)->update('purchase_limiter', $update_data);
			echo json_encode($update_data);
		}
		else
		{
			$this->db->insert('purchase_limiter', $insert_data);
			echo json_encode($insert_data);
		}
	}
	
	public function edit_plimit($id)
	{
		$data['plimit_data'] = $this->db->where('id', $id)->get('purchase_limiter')->row();
		$this->load->view('offers/modals/edit_plimit', $data);
	}

	public function plimit_toggle()
	{
		$status = ($this->input->post('status') == 'true') ? 'checked' : '';
		$data = array(
			'status' => $status
		);
		$this->db->where('id', $this->input->post('id'));
		echo ($this->db->update('purchase_limiter', $data)) ? 'success' : 'failed';
	}
	
	public function active_plimit_window()
	{
		$this->load->view('offers/sublists/active_plimit_window');
	}
	
}