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
		$data['pointers']=$this->db->select('id,title')->get_where('offer_pointer_groups',array('deleted'=>0))->result();
		$data['locations']=$this->db->select('id,title')->get_where('offer_location_groups',array('deleted'=>0))->result();
		$this->load->view('offers/modals/view_basic_form',$data);
	}

	public function save_basic(){
		
		$response = array();
		$valid_date_range = 0;
		$array['title'] = $this->input->post('title');
		$array['location_group_id']  = $this->input->post('locations');
		$array['pointer_group_id'] = $this->input->post('pointers');
		$array['start_time'] = $this->input->post('start_time');
		$array['end_time'] = $this->input->post('end_time');
		$array['discount'] = $this->input->post('discount');

		$this->db->insert('dynamic_prices', $array);
		echo "Created Successfully";
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

	public function offer_toggle2($tblname)  // either 1 or 0 for status
	{
		$status = ($this->input->post('status') == 'true') ? 1 : 0;
		$data = array(
			'status' => $status
		);
		$this->db->where('id', $this->input->post('id'));
		echo ($this->db->update($tblname, $data)) ? 'success' : 'failed';
	}

	public function get_dynamic_prices()
	{
		//echo"<pre>";print_r($this->session->userdata());
		$data['dynamic_prices'] = $this->db->order_by('id','DESC')->get('dynamic_prices')->result_array();
	//echo $this->db->last_query(); die;
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
	public function cashier_add2($loc_name='',$loc_id='',$array=''){
			$loc_name = $this->input->get('loc_name');
			$loc_id   = $this->input->get('loc_id');
			$id       = $this->input->get('id');
			
		foreach($this->Pricing->get_active_shops(array('shop', 'dbf', 'hub')) as $row)
			{
				$shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
			}
		$ids = explode(',',$id);

		$data['shops']    = $shops;
		$data['loc_name'] = $loc_name;
		$data['loc_id']   = $loc_id;
		$data['id']       = $ids;
		
		$this->load->view('offers/modals/add_cashier2',$data);
	}

		public function add_cashier_loc(){
		$cashier_id  = $this->input->post('name');
		$location_id = $this->input->post('location_id');
		
		$data = array('cashier_id'=>$cashier_id,
					'person_id'=>$location_id,
					);
		$this->db->insert('cashier_shops',$data);
		if($data){
			echo 'Added Successfully.';
		}
	}

	public function active_plimit_window()
	{
		$this->load->view('offers/sublists/active_plimit_window');
	}
	// Control Pannel ----- Cashiers
	public function get_cashiers()
	{	
		$loc_owner =  $this->input->get('loc_owner');
		$id  = explode(',',$loc_owner);
		$data['shop_details'] = $this->Employee->get_shop_details($loc_owner);
		$data['cashiers'] = $this->Employee->get_cashiers($loc_owner);
		$data['time']     = $this->Employee->get_time($id[0]);
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
    echo ($this->db->update('cashiers', $data)) ? 'success' : 'failed';
  }
	
	public function edit_cashier_data(){
		$data = $this->input->post();
		$update_array = array(
			$data['col'] => $data['data']
		);
		 $query = $this->db->where('id',$data['id'])
		 ->update('cashiers',$update_array);
			echo $data['col']." updated successfully!";
	}
	public function create_voucher(){
		$this->load->view("offers/subviews/create_vouchers");
	}
	public function sub_gc_detail(){	
		$data['voucher_id'] = $this->input->post('vc_value');
		$data['created_at'] = date('Y-m-d H:i:s',time());
		$data['voucher_code'] = strtoupper($this->Giftcard->random_code(8));
		//Duration of Expiry
		$this->db->select();
		$this->db->where('tag','vc_expiry_duration');
		$duration = $this->db->get('custom_fields')->row()->int_value;
		//Addition of Duration and current time
		$expiry_date = date_create($data['created_at']);
		date_add($expiry_date,date_interval_create_from_date_string($duration."days"));
		$data['expiry_date'] = date_format($expiry_date,"Y-m-d");
		$this->db->insert('voucher_gifts', $data);
		echo "success";
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
		//$data['plans'] = $this->Pricing->get_core_plans();
		$this->load->view("offers/submodules/dynamic_pricing");
	}

	public function view_vouchers(){
		$this->load->view("offers/submodules/vouchers");
	}
	// All Voucher view 
	public function voucher_try(){

			$post = $this->input->post('myData');
			$vouchers_id['vou_id'] = $post;
			$this->load->view('offers/subviews/all_voucher_display', $vouchers_id);
	}

	public function all_vouchers(){
		$post = $this->input->post('voucher_ids');
		if(!empty($post))
		{
			$vouchers_arr  = array();
			foreach ($post as $voucher_ids ){
				$vouchers_arr [] = $voucher_ids;
				
			}
		$count = count($vouchers_arr);
			if($count > 18){?>
				<script>
						alert("You can not select more than 18 vouchers!");
				</script>
				<?php 
				$this->load->view("offers/submodules/vouchers");
			}
			else {
			$vouchers_id['vou_id'] = $vouchers_arr;
			$this->load->view('offers/subviews/all_voucher_display', $vouchers_id);
			}
		}
		else{
			?> 
					<script>alert("Select Gift Vouchers");</script>
		<?php 
					$this->load->view("offers/submodules/vouchers");
		}
}

	public function all_gc_views(){
		$this->db->select('voucher_gifts.* , vc_gift_master.title as title, vc_gift_master.vc_value as vc_value');
		$this->db->from('voucher_gifts');
		$this->db->join('vc_gift_master','voucher_gifts.voucher_id=vc_gift_master.id','inner');
		$this->db->order_by('created_at','desc');
		$data['vc_info'] = $this->db->get()->result();
		$this->load->view('offers/sublists/gift_vc',$data);
	}

	public function edit_gift_vc($id){
		$data['id']=$id;
		$this->load->view("offers/subviews/edit_gift_vc",$data);
	}

	public function change_custom_status(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$data = $this->Offers_manage->update_status($id,$status);
	}

	public function view_gift_vc($id){
		$data['id']=$id;
		$this->load->view("offers/subviews/display_created_vc",$data);
	}
	public function load_custom_tab(){
		$this->load->view('offers/subviews/custom_fields');
	}
	public function get_custom_tags(){
		$id = $this->input->post('id');
		$data['data'] = $this->Offers_manage

		->get_custom_data('tag',$id);
		return $this->load->view('offers/subviews/custom_field_table',$data); 
	}
	public function view_purchase_limits(){
		$data['mci_data'] = $this->Item->get_mci_data('all');
		$this->load->view("offers/submodules/purchase_limits",$data);
	}

	public function view_control_panel(){
		$data['locations'] = $this->Stock_location->get_allowed_locations2();
		$this->load->view("offers/submodules/control_panel",$data);
	}
	public function display_created_vouchers(){
		$data['result']= $this->input->get('data');
		$this->load->view("offers/subviews/display_created_vc",$data);
	}

	public function delete_gift_vc(){
		$id = $this->input->post(id);
		$this->db->delete('voucher_gifts',array('id'=>$id));
		echo "Deleted Succesfully";
	}
	public function delete_all_gift_vc(){
		$this->db->empty_table('voucher_gifts');
		echo "Deleted Succesfully";
	}
	//create locations groups
	public function create_locations_group(){
		$data['locations'] = $this->Stock_location->get_allowed_locations2();
		$this->load->view('offers/subviews/create_locations_group',$data);
	}

	//insert created location groups
	public function insert_loc_group(){
		$data['locations'] = json_encode($this->input->post('loc_group'));
		$data['title'] = $this->input->post('title');
		$data['created_at']= date('Y-m-d H:i:s',time());
		 $this->db->insert('offer_location_groups',$data);
	}

	public function load_cashier(){
		$data['locations'] = $this->Stock_location->get_allowed_locations2();
		$this->load->view('offers/subviews/cashier',$data);
	}
	
	public function cashier_add(){
    foreach($this->Pricing->get_active_shops(array('shop', 'dbf', 'hub')) as $row)
		{
			$shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
		}
		$data['shops'] = $shops;
		$this->load->view('offers/modals/add_cashier',$data);
	}

	public function cashier_edit_view($id){
	$data['detail'] = 	$this->db->select()->get_where('cashiers',array('id'=>$id))->row();
		foreach($this->Pricing->get_active_shops(array('shop', 'dbf', 'hub')) as $row)
		{
			$shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
		}
		$data['shops'] = $shops;
		$this->load->view('offers/modals/edit_cashier',$data);
	}
	public function cashier_edit(){
		$data['name'] = $this->input->post('name'); 
		$data['contact'] = $this->input->post('contact'); 
		$data['webkey'] = $this->input->post('webkey'); 
		$shops= $this->input->post('shops'); 
		$cashier_id= $this->input->post('cashier_id'); 

		$this->db->delete('cashier_shops',array('cashier_id'=>$cashier_id));
		$this->db->where('id',$cashier_id);
		$this->db->update('cashiers',$data);
		if(!empty($shops)){
			foreach($shops as $person_id){
				$this->db->insert('cashier_shops',array('cashier_id'=>$cashier_id,'person_id'=>$person_id));
			}
		}
		echo 'Edited Successfully.';
	}
	public function edit_loc($action){
		$loc_id = $this->input->post('loc_id');
		$cashier_id = $this->input->post('cashier_id');
		if($action=='insert'){
			$data = array('cashier_id'=>$cashier_id,
									'person_id'=>$loc_id
								);
			$this->db->insert('cashier_shops',$data);
			echo 'Added Successfully.';
		}else{
			$data = array('cashier_id'=>$cashier_id,
									'person_id'=>$loc_id
								);
			$this->db->where($data);
			$this->db->delete('cashier_shops');
			echo 'Deleted Successfully.';
		}
	}
		public function cashier_save(){
		
		$shops = $this->input->post('shops'); //array

		$data['name'] = $this->input->post('name'); 
		$data['contact'] = $this->input->post('contact'); 
		$data['webkey'] = $this->input->post('webkey');
		$location_id = !empty($this->input->post('location_id'))?$this->input->post('location_id'):''; 

		$row_count = 	$this->db->get_where('cashiers',array('name'=>$data['name']))->num_rows();
		
		if(!empty($location_id)){
			$shops[] = $location_id;
		}

		if($row_count>0){
			echo TRUE;
		}else{
			$this->db->insert('cashiers',$data);
			$cashierId = $this->db->insert_id();
			if($shops!=''){
				foreach($shops as $shop){
					$data_loc['cashier_id'] = $cashierId;
					$data_loc['person_id'] = $shop;
					$this->db->insert('cashier_shops',$data_loc);
				}
			}
			echo FALSE;
		}	
		
	}
	public function load_cashier_details(){
		$this->load->view('offers/subviews/cashier_details');
	}

	public function load_loc_group(){
		$data['loc_group']= $this->db->get_where('offer_location_groups',array('deleted'=>0))->result();
		$data['locations'] = $this->Stock_location->get_allowed_locations2();
		$this->load->view('offers/subviews/locations_groups',$data);
	}

	public function edit_loc_group($id){
		$this->db->select('username,person_id');
		$this->db->where(array('deleted'=>0));
		 $data['users'] = $this->db->get('employees')->result();
		 $data['row']=$this->db->get_where('offer_location_groups',array('id'=>$id))->row();
		 $data['id']=$id;
		$this->load->view('offers/subviews/edit_loc_groups',$data);
	}

	public function update_loc_group($id){
		$data['locations'] = json_encode($this->input->post('loc_group'));
		$data['title'] = $this->input->post('title');
		$data['updated_at']= date('Y-m-d H:i:s',time());
		$this->db->set($data);
		$this->db->where('id',$id);
		$this->db->update('offer_location_groups');
	}

	public function delete_cashier(){
		$location_id = $this->input->post('location_id');
		$cashier_id = $this->input->post('cashier_id');
		$where=array('cashier_id'=> $cashier_id,'person_id'=>	$location_id);
		$this->Control_Panel->delete_entire_row('cashier_shops',$where);
		echo 'Success';
	}

	public function view_location_group_table(){
		$data['loc_group']= $this->db->get_where('offer_location_groups',array('deleted'=>0))->result();
		$this->load->view('offers/sublists/locations_group_table',$data);
	}

	public function load_offer_bundle(){
	//	$data['bundles']=$this->db->get_where('offer_pointer_groups',array('deleted'=>0))->result();
		$this->load->view("offers/subviews/offer_bundle");
	}

	public function create_offer_bundle(){
		$data['categories']=	$this->db->get('master_categories')->result();
		$this->load->view('offers/subviews/create_bundle',$data);
	}
	public function insert_offer_bundle(){
		if($this->input->post('type')=='tags'){
			$post_type = 'categories';
			$tag_ids = $this->input->post('bundle');
		
			$this->db->select('GROUP_CONCAT(id) as id');
			$this->db->where_in('tag',$tag_ids);
			$category_ids = $this->db->get('master_categories')->row()->id;
			$post_bundle = explode(',',	$category_ids);
		}else{
			$post_bundle = $this->input->post('bundle');
			$post_type = $this->input->post('type');
			$post_barcode = $this->input->post('barcode');
		}

		$parent_id = $this->input->post('parent_id');
		if($post_type!='barcode'){
			$bundle = array(
				'type' =>  $post_type,
				'parent_id' => $parent_id,
				'entities' =>$post_bundle
			);
		}else{
			$bundle = array(
				'type' =>  $post_type,
				'barcode' => $post_barcode
			);
		}
	
		$data['bundle'] = json_encode($bundle);
		$data['title'] = $this->input->post('title');
		$data['created_at']= date('Y-m-d H:i:s',time());
		$this->db->insert('offer_pointer_groups',$data);
	}
	public function fetch_subcategory($id){
		$data = $this->db->get_where('master_subcategories',array('parent_id'=>$id))->result();
		$result="";
		foreach($data as $row){
			$result .= "<option value='".$row->id."'>".$row->name."</option>";
		}
		echo $result;
	}
	public function fetch_brands(){
		$data = $this->db->get('master_brands')->result();
		$result="";
		foreach($data as $row){
			$result .= "<option value='".$row->id."'>".$row->name."</option>";
		}
		echo $result;
		}

	public function fetch_categories(){
		$data = $this->db->get('master_categories')->result();
		$result="";
		foreach($data as $row){
			$result .= "<option value='".$row->id."'>".$row->name."</option>";
		}
		echo $result;
		}
		public function add_tags(){
			$title     = $this->input->post('title');
			$alias     = $this->input->post('alias');
			$int_value = $this->input->post('int_value');
			$tag_name  = $this->input->post('tag_name');

			$data = array('title'=>$title,'alias'=>$alias,'int_value'=>$int_value,'tag'=>$tag_name);
			$result = $this->Offers_manage->add_tags($data);

			return $result;

		}
		public function fetch_tags(){
			$this->db->select('id, alias as name');
			$this->db->where('tag','category_tag');
			$data = $this->db->get('custom_fields')->result();
			$result="";
			foreach($data as $row){
				$result .= "<option value='".$row->id."'>".$row->name."</option>";
			}
			echo $result;
		}

		public function create_tag($tag_name=''){
			$tag_name1 = $this->input->post('tag_name');
			echo $tag_name1;
			$data['data'] = $tag_name;
			$this->load->view('offers/subviews/create_tag',$data);
		}
		public function view_offer_bundle_table(){
			$data['bundles']=$this->db->get_where('offer_pointer_groups',array('deleted'=>0))->result();
			$this->load->view('offers/sublists/offer_bundle_table',$data);
		}

		public function edit_bundle_group($id){
			$data['categories']=	$this->db->get('master_categories')->result();	
			$data['row']=	$this->db->get_where('offer_pointer_groups',array('id'=>$id))->row();	
			$this->load->view('offers/subviews/edit_bundle',$data);
		}
		public function update_bundle_group($id){
			$parent_id = $this->input->post('parent_id');
			$bundle = array(
					'type' =>  $this->input->post('type'),
						'parent_id' => $parent_id,
					'entities' =>$this->input->post('bundle')
				);
			$data['bundle'] = json_encode($bundle);
			$data['title'] = $this->input->post('title');
			$data['updated_at']= date('Y-m-d H:i:s',time());
			$this->db->where('id',$id);
			$this->db->update('offer_pointer_groups',$data);
		}

		public function load_subview($view){
			$url = "offers/subviews/".$view;
			$this->load->view($url);
		}

		public function get_pointer_group($id){
			$bundle = $this->db->select('bundle')->get_where('offer_pointer_groups',array('id'=>$id))->row()->bundle;
			$entities =	$this->Control_Panel->fetch_title($bundle);
			echo substr($entities,0,-2);
		}

		public function get_loc_group($id){
			$obj = $this->db->select('locations')->get_where('offer_location_groups',array('id'=>$id))->row()->locations;
			$locations =	$this->Control_Panel->fetch_username($obj,'employees');
				 echo substr($locations,0,-2);
		}
	public function delete_row($tablename='offer_location_groups', $column='location_group_id', $id=10){
		$data = $this->db->select()->get_where('dynamic_prices',array('status'=>1,$column=>$id))->row();
		
	}

	public function save_time(){
		$open_time  = $this->input->post('open_time');
		$close_time = $this->input->post('close_time');
		$data['login']  = $open_time;
		$data['logout'] = $close_time;
		$ids = $this->input->post('location_id');
		$id  = explode(',',$ids); 
		$this->db->where('location_owner',$id[0]);
		$data = $this->db->update('ospos_stock_locations',$data);
		if($data){
			echo 'done';
		}
		else{
			echo 'error';
		}
	}	
}


