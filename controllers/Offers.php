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
		$this->load->view('offers/dashboard');
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
		//echo"<pre>";print_r($this->session->userdata());
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
	// Control Pannel ----- Cashiers
	public function get_cashiers()
	{	
		$loc_owner =  $this->input->get('loc_owner');
		$data['shop_details'] = $this->Employee->get_shop_details($loc_owner);
		$data['cashiers'] = $this->Employee->get_cashiers($loc_owner);
		$this->load->view('offers/subviews/shop_cpanel', $data);
	
	}
	public function save_cp_changes(){
		$loc_owner =  $this->input->get('loc_id');
		$update_data = $this->input->get('update_data');
		$type=$this->input->get('type');

		$result = $this->Employee->update_row('stock_locations',array('location_owner'=>$loc_owner),array($type=>$update_data));
		 if($result){
			 echo "Updated Successfully!";
		 }else{
			 echo "Something went wrong....";
		 }
	}
	public function edit_cashier($id){

		$data['cashier_data'] = $this->Employee->select_row('cashiers',array('id'=>$id));
		$this->load->view('offers/subviews/edit_cashier_form',$data);
	}
	public function update_cashier_data(){
			print_r( $this->input->post('data'));

			$data= $this->form_validation->set_rules('name', 'First Name',"numeric"); 
			if ($this->form_validation->run() == FALSE){
				$errors= $this->form_validation->error_array();
			echo"<pre>";	print_r($errors);
				
			}else{
				echo "success";
			}
	}

	public function cashier_toggle()
  {
    $status = ($this->input->post('status') == 'true') ? 'checked' : '';
    $data = array(
      'status' => $status
    );
    $this->db->where('id', $this->input->post('id'));
    echo ($this->db->update('cashiers2', $data)) ? 'success' : 'failed';
  }
	
	public function edit_cashier_data(){
		$data = $this->input->post();
		$update_array = array(
			$data['col'] => $data['data']
		);
		 $query = $this->db->where('id',$data['id'])
		 ->update('cashiers2',$update_array);
			echo $data['col']." updated successfully!";
	}
	public function create_voucher(){
		$this->load->view("offers/submodules/create_vouchers");
	}
	public function sub_gc_detail(){
		$response['vc_bg_img'] = $this->input->post('vc_bg_img');
		$vc_count = $this->input->post('vc_count');
		$insert_ids = array();
		   $data['voucher_id'] = $this->input->post('vc_value');
		 	 $data['exp_date'] = $this->input->post('vc_exp_date');
		   $data['created_at']= date('Y-m-d H:i:s',time());
		for($i=1;$i<=$vc_count;$i++){
			$data['code'] = strtoupper($this->Giftcard->random_code(8));
			$this->db->insert('voucher_gifts', $data);
			$insert_ids[] = $this->db->insert_id();
		}
		$response['insert_ids']=$insert_ids;
		$rows = $this->db->get('voucher_gifts')->result_array();
		print_R($rows);
	}

	public function get_gift_vc_options(){
		$data="";
		$query = $this->db->get('vc_gift_master')->result_array();
		foreach($query as $row){
				$data .= '<option value="'.$row['id'].'">'.$row['title'].' - '.$row['vc_value'].'</option>';
		}
		 echo $data;
	}

	public function view_dynamic_pricing(){	
		$data['plans'] = $this->Pricing->get_core_plans();
		$this->load->view("offers/submodules/dynamic_pricing",$data);
	}

	public function view_vouchers(){
		$this->load->view("offers/submodules/vouchers");
	}

	public function view_purchase_limits(){
		$data['mci_data'] = $this->Item->get_mci_data('all');
		$this->load->view("offers/submodules/purchase_limits",$data);
	}

	public function view_control_panel(){
		$data['locations'] = $this->Stock_location->get_allowed_locations2();
		$this->load->view("offers/submodules/control_panel",$data);
	}

}