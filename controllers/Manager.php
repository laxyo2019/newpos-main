<?php //ini_set('memory_limit', '-1'); ?>

<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Manager extends Secure_Controller
{
  public function __construct()
	{
		parent::__construct('manager');
  }

  public function index()
  {
    $this->load->view('manager/dashboard');
  }

  public function load_tab_view($page,$folder=''){
      $url = "manager/tabs/".$folder.'/'.$page;
      $this->load->view($url);
  }
  public function get_valid_customers($password)
  {
    if(isset($password) && $password == 'mechtech5') {
      $this->load->view('manager/get_valid_customers');
    }else{
      echo 'Unauthorised Action';
    }
  }

  public function fetch_valid_customers()
  {
    $customers = $this->input->post('customers');
    $cArray = explode(PHP_EOL, $customers);
    foreach($cArray as $row) {
      if(!empty($row)) {
        if(is_numeric($row) && strlen($row) == 10) {
          if (count(array_keys($cArray, $row)) == 1) {
            echo ($row . "\n");
          }
        }
      }
    }
  }

  public function populate_vc_out_table($password)
  {
    if(isset($password) && $password == 'mechtech5') {
      $db_customers = $this->db->get('people')->result();

      foreach ($db_customers as $row) {
        $contact = $row->phone_number;
        if(!empty($contact)) {
          if(is_numeric($contact) && strlen($contact) == 10) {
            $filtered_customers['phone_number'] = $contact;
            $filtered_customers['customer_id'] = $row->person_id;
          }
          $response[] = $filtered_customers;
        }
      }

      foreach($response as $row) {
        $data = array(
          'voucher_id' => 3,
          'phone' => $row['phone_number'],
          'customer_id' => $row['customer_id']
        );

        $this->db->insert('special_vc_out', $data);
      }
      redirect(site_url('manager'));
    }
    else {
      echo 'Unauthorised Action!';
    }

  }

  public function get_count()
  {
    $count = 0;
    $locations = $this->input->post('locations');

    $filter = $this->input->post('filter');
    foreach($filter as $key=>$value) {
      if(!empty($value)) {
        $array[$key] = $value;
      }
    }

    $this->db->where('deleted', 0);
    $this->db->where($array);
    $filtered_items = $this->db->get('items')->result_array();

    foreach($locations as $location) {
      foreach($filtered_items as $row) {
        $this->db->where('location_id', $location);
        $this->db->where('item_id', $row['item_id']);
        $count += $this->db->get('item_quantities')->row()->quantity;
      }
    }

    echo $count;
  }

  public function count_all_items()
  {
    $locations = $this->input->post('locations');

    $locations = (!is_array($locations)) ? json_decode($locations) : $locations;

    $this->db->select_sum('quantity');
    $this->db->from('item_quantities AS first');
    $this->db->join('items AS sec','first.item_id = sec.item_id','inner');
    $this->db->where_in('first.location_id', $locations);
    $this->db->where('sec.deleted',0);
    $query = $this->db->get();  
    $count = $query->row();
    //echo $this->db->last_query();
    echo $count->quantity;
  }

  public function mci_livesearch()
  {
    $keyword = $this->input->post('keyword');
    $table_name = 'master_'.$this->input->post('type');
    $this->db->like('name', $keyword);
    $results = $this->db->get($table_name)->result_array();
    foreach($results as $row)
    {
     echo '<span style="background-color:gray; color:#fff" class="form-control input-sm liveresults">'.$row['name'].'</span>';
    }
  }
public function items_undelete_data($id){
  $this->db->select();
  $this->db->where('parent_id',$id);
  $data['items']=$this->db->get('ospos_sheet_undelete')->result();
  $this->load->view('manager/tabs/inventory/items_undelete_data',$data);
}
  // public function list_all_items($location_id)
  // {
   
  //   //$data['items'] = $this->db->where('deleted', 0)->limit(5)->get('items')->result_array();
  //   //$data['location_id'] = $location_id;

  
  //   $this->db->select('items.*, item_quantities.quantity, GROUP_CONCAT(percent) AS percent');
  //   $this->db->from('items');
  //   $this->db->join('item_quantities','item_quantities.item_id=items.item_id','inner');
  //   $this->db->join('items_taxes','items_taxes.item_id=items.item_id','inner');
  //   $this->db->where(array('item_quantities.location_id'=>$location_id));
  //   $this->db->where(array('items.deleted'=> 0));
  //  // $this->db->limit(50);
  //   $this->db->group_by('item_quantities.item_id');
  //   $query= $this->db->get();
  //   $data['items']=$query->result();
  //  // echo $this->db->last_query();
  //   $this->load->view('manager/sublists/all_items_sublist', $data);
  // }

  public function list_all_items($location_id)
 {
 
   //$data['items'] = $this->db->where('deleted', 0)->limit(5)->get('items')->result_array();
   //$data['location_id'] = $location_id;

    $this->db->select('items.*, item_quantities.quantity, GROUP_CONCAT(percent) AS percent');
   $this->db->from('items');
   $this->db->join('item_quantities','item_quantities.item_id=items.item_id','inner');
   $this->db->join('items_taxes','items_taxes.item_id=items.item_id','inner');
   $this->db->where(array('item_quantities.location_id'=>$location_id));
   $this->db->where(array('items.deleted'=> 0));
   //$this->db->limit(50);
   $this->db->group_by('item_quantities.item_id');
   $query= $this->db->get();
   $items=$query->result();
   // echo $this->db->last_query();
   // $this->load->view('manager/sublists/all_items_sublist', $data);

   $filename = 'DBF '. $this->Stock_location->get_location_name($location_id). ' POS'.date('d-m').'.csv';
   header("Content-Description: File Transfer");
   header("Content-Disposition: attachment; filename=$filename");
   header("Content-Type: application/csv; ");

   // file creation
   $file = fopen('php://output', 'w');
   $custom_attributes = $this->Appconfig->get_additional_ten_col_name();
   foreach($custom_attributes as $custom_attribute){
        $custom_col[]=$custom_attribute->value;
    }
   $header = array("ID","Barcode","Item Name","Category","SubCategory","Brand","Size","Color","Model","MRP","HSN","Stock Edition","CGST","SGST","IGST","Disc % (Retail)","Disc % (Wholesale)","Disc % (Franchise)","FP (Retail)","FP (Wholesale)","FP (Franchise)","Quantity",$custom_col[1],$custom_col[2],$custom_col[3],$custom_col[4],$custom_col[5],$custom_col[6],$custom_col[7],$custom_col[8],$custom_col[9],$custom_col[0]);
   fputcsv($file, $header);

   foreach ($items as $item){

     $ds = json_decode($item->discounts);
     $fp = json_decode($item->cost_price);
     $tax_array= explode(',',$item->percent);
     // print_R($tax_array);die;
    
    $CGST= isset($tax_array[0]) ? $tax_array[0] : 0;
    $IGST= isset($tax_array[1]) ? $tax_array[1] : 0;
    $SGST= isset($tax_array[2]) ? $tax_array[2] : 0;
    
      $ds_retail = (isset($ds->retail)) ? $ds->retail : "";
      $ds_wholesale = (isset($ds->wholesale)) ? $ds->wholesale : "";
      $ds_franchise = (isset($ds->franchise)) ? $ds->franchise : "";
 
      $fp_retail = (isset($fp->retail)) ? $fp->retail : "";
      $fp_wholesale = (isset($fp->wholesale)) ? $fp->wholesale : "";
      $fp_franchise = (isset($fp->franchise)) ? $fp->franchise : "";

       fputcsv($file,array(
        $item->item_id ,
           $item->item_number,
           $item->name,
           $item->category,
           $item->subcategory,
           $item->brand,
           $item->custom2,
           $item->custom3,
           $item->custom4,
           $item->unit_price,
           $item->custom1,
           $item->custom6,
           $CGST ,
           $SGST,
           $IGST,
           round($ds_retail),
           round($ds_wholesale),
           round($ds_franchise),
           round($fp_retail),
           round($fp_wholesale),
           round($fp_franchise),
           $item->quantity,
           $item->column1,
           $item->column2,
           $item->column3,
           $item->column4,
           $item->column5,
           $item->column6,
           $item->column7,
           $item->column8,
           $item->column9,
           $item->column10
                         ));
   }

   fclose($file);
   exit;

 }

 public function list_all_locations($location_id)
 {
    $this->db->select('items.*, item_quantities.quantity, GROUP_CONCAT(percent) AS percent, item_quantities.item_id, GROUP_CONCAT(CONCAT(CONCAT(CONCAT("""","loc_",""),location_id,""""),":",concat("""",quantity,"""")))as All_locations ');
    $this->db->from('item_quantities');
    $this->db->join('items','items.item_id=item_quantities.item_id','inner');
    $this->db->join('items_taxes','items_taxes.item_id=items.item_id','inner');
    $this->db->where(array('items.deleted'=> 0));
    $this->db->group_by('item_quantities.item_id');
    $query= $this->db->get();
    $items=$query->result();  
  
   $filename = 'DBF All LOCATIONS'.date('d-m').'.csv';
   header("Content-Description: File Transfer");
   header("Content-Disposition: attachment; filename=$filename");
   header("Content-Type: application/csv; ");

   // file creation
   $file = fopen('php://output', 'w');
   $custom_attributes = $this->Appconfig->get_additional_ten_col_name();
   foreach($custom_attributes as $custom_attribute){
        $custom_col[]=$custom_attribute->value;
    }
   $header = array("ID","Barcode","Item Name","Category","SubCategory","Brand","Size","Color","Model","MRP","HSN","CGST","SGST","IGST","Disc % (Retail)","Disc % (Wholesale)","Disc % (Franchise)","FP (Retail)","FP (Wholesale)","FP (Franchise)", "AP_Quantity",  "BT_Quantity", "IP_Quantity", "KDR_Quantity", "LH_Quantity", "MH_Quantity","SHOP114_Quantity", $custom_col[1],$custom_col[2],$custom_col[3],$custom_col[4],$custom_col[5],$custom_col[6],$custom_col[7],$custom_col[8],$custom_col[9],$custom_col[0]);
   fputcsv($file, $header);

   foreach ($items as $item){
    $loc = '{'.$item->All_locations.'}';
    $al= json_decode($loc);
   
     $ds = json_decode($item->discounts);
     $fp = json_decode($item->cost_price);
     $tax_array= explode(',',$item->percent);
   
    
    $CGST= isset($tax_array[0]) ? $tax_array[0] : 0;
    $IGST= isset($tax_array[1]) ? $tax_array[1] : 0;
    $SGST= isset($tax_array[2]) ? $tax_array[2] : 0;
      
      $ds_retail = (isset($ds->retail)) ? $ds->retail : "";
      $ds_wholesale = (isset($ds->wholesale)) ? $ds->wholesale : "";
      $ds_franchise = (isset($ds->franchise)) ? $ds->franchise : "";
 
      $fp_retail = (isset($fp->retail)) ? $fp->retail : "";
      $fp_wholesale = (isset($fp->wholesale)) ? $fp->wholesale : "";
      $fp_franchise = (isset($fp->franchise)) ? $fp->franchise : "";

      $al_8 = (isset($al->loc_8)) ? $al->loc_8: 0;
      $al_16 = (isset($al->loc_16)) ? $al->loc_16: 0;
      $al_6 = (isset($al->loc_6)) ? $al->loc_6: 0;
      $al_21 = (isset($al->loc_21)) ? $al->loc_21: 0;
      $al_4 = (isset($al->loc_4)) ? $al->loc_4: 0;
      $al_11 = (isset($al->loc_11)) ? $al->loc_11: 0;
      $al_20 = (isset($al->loc_20)) ? $al->loc_20: 0;
       
      fputcsv($file,array(
        $item->item_id ,
           $item->item_number,
           $item->name,
           $item->category,
           $item->subcategory,
           $item->brand,
           $item->custom2,
           $item->custom3,
           $item->custom4,
           $item->unit_price,
           $item->custom1,
           $CGST ,
           $SGST,
           $IGST,
           round($ds_retail),
           round($ds_wholesale),
           round($ds_franchise),
           round($fp_retail),
           round($fp_wholesale),
           round($fp_franchise),
           $al->loc_8,
           $al->loc_16,
           $al->loc_6,
           $al->loc_21,
           $al->loc_4,
           $al->loc_11,
           $al->loc_20,
           $item->column1,
           $item->column2,
           $item->column3,
           $item->column4,
           $item->column5,
           $item->column6,
           $item->column7,
           $item->column8,
           $item->column9,
           $item->column10
       ));
   }
   fclose($file);
   exit;
  }
 public function list_filtered_items($location_id)
  {
    $filter = $this->input->post('filter');
    foreach($filter as $key=>$value) {
      if(!empty($value)) {
        $array[$key] = $value;
      }
    }

    // $this->db->select('items.*, GROUP_CONCAT(percent) AS percent, item_quantities.item_id, GROUP_CONCAT(CONCAT(CONCAT(CONCAT("""","loc_",""),location_id,""""),":",concat("""",quantity,"""")))as All_locations ');

    $this->db->select('items.*','item_quantities.item_id AS item_quantities_item_id','item_quantities.quantity, GROUP_CONCAT(percent) AS percent, item_quantities.item_id, GROUP_CONCAT(CONCAT(CONCAT(CONCAT("""","loc_",""),location_id,""""),":",concat("""",quantity,"""")))as All_locations ');

    $this->db->from('item_quantities');
    $this->db->join('items','items.item_id=item_quantities.item_id','inner');
    $this->db->join('items_taxes','items_taxes.item_id=items.item_id','inner');
    if($location_id=='all'){

    }else {
	    $this->db->where('location_id', $location_id); 
	  }
    $this->db->where(array('items.deleted'=> 0));
    $this->db->group_by('item_quantities.item_id');
    $this->db->where($array);
    $data['itemss'] = $this->db->get()->result_array();
    // echo $this->db->last_query();
    $data['selected_location']=$location_id;
    if($location_id =='all'){
      $this->load->view('manager/sublists/items_sublist', $data );
    }else{
       $this->load->view('manager/sublists/items_sublist', $data );
    }
  }
  // public function report_sales()
  // {
  //   // $data['locations'] = $this->input->post('locations');
  //   $start_date = $this->input->post('start_date');
  //   $end_date = $this->input->post('end_date');

  //   $filter = $this->input->post('filter');

  //   if($filter == 'all')
  //   {
  //     $array = array();
  //   }
  //   else
  //   {
  //     foreach($filter as $key=>$value)
  //     {
  //       if(!empty($value)){
  //         $array[$key] = $value;
  //       }
  //     }
  //   }

  //   $this->db->where('deleted', 0);
  //   $this->db->where($array);
  //   $result = $this->db->get('items')->result_array();
  //   foreach($result as $row)
  //   {
  //     $result_items[] = $row['item_id'];
  //   }

  //   $this->db->select('
  //     sales.sale_time AS sale_time,
  //     sales.customer_id AS customer_id,
  //     sales.employee_id AS employee_id,
  //     sales.invoice_number AS invoice_number,
  //     sales.sale_id AS sale_id,
  //     sales_items.item_id AS item_id,
  //     sales_items.quantity_purchased AS quantity,
  //     sales_items.item_unit_price AS item_price,
  //     sales_items.discount_percent AS item_discount,
  //     sales_items.item_location AS item_location,
  //   ');
  //   $this->db->from('sales');
  //   $this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id');
  //   $this->db->where('sale_time >=', date('Y-m-d H:i:s', strtotime($start_date)));
  //   $this->db->where('sale_time <=', date('Y-m-d H:i:s', strtotime($end_date)));
  //   $this->db->where_in('item_id', $result_items);
  //   $data['report_results'] = $this->db->get()->result_array();
  //   $this->load->view('manager/sublists/report_sales', $data);
  // }

   public function tally_report()
  {
  	$data['mci_data'] = $this->Item->get_mci_data('all');
    $this->load->view('manager/modals/tally_report',$data);
  }
	public function get_subcategory(){
		$category_name = $this->input->post('category');
		$category_id = $this->db->get_where('master_categories',array('name'=>$category_name))->row()->id;
		$subcategory  = $this->db->get_where('master_subcategories',array('parent_id'=>$category_id))->result();
		$data = '';
		foreach($subcategory as $row){
			$data .= '<option value="'.$row->name.'">'.$row->name.'</option>';
		}
		echo $data;
	}
	public function get_filtered_sales(){
		$category = $this->input->post('category');
		$subcategory = $this->input->post('subcategory'); 
		$brand = $this->input->post('brand');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

    $result_items = array();

    $this->db->select('
      sales.sale_id AS sale_id,
      items.category AS category,
      items.subcategory AS subcategory,
      items.brand AS brand,
      items.item_number AS item_number,
      items.name AS name,
      items.custom1 AS custom1,
      items.custom6 AS custom6,
      items.unit_price AS unit_price,
      sales.sale_time AS sale_time,
      sales.customer_id AS customer_id,
      sales.tally_number AS tally_number,
      sales.employee_id AS employee_id,
      sales.sale_status AS sale_status,
      sales.sale_type AS sale_type,
      sales.bill_type AS bill_type,

      sales_items.item_id AS item_id,
      sales_items.quantity_purchased AS quantity,
      sales_items.item_unit_price AS item_price,
      sales_items.discount_percent AS item_discount
    ');

    $this->db->from('sales');
    $this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id');
    $this->db->join('items', 'sales_items.item_id = items.item_id');
    if($category != ''){
    	$this->db->where('items.category',$category);
    }
    if($subcategory != ''){
    	$this->db->where('items.subcategory',$subcategory);
    }
    if($brand != ''){
    	$this->db->where('items.brand',$brand);
    }
    $this->db->where('DATE(sale_time) BETWEEN "'.rawurldecode($start_date).'" AND "'.rawurldecode($end_date).'"');
    $data['report_results'] = $this->db->get()->result_array();
    
    return $this->load->view('manager/sublists/filtered_sales_items',$data);
	}
  public function tally_format($start_date,$end_date)
  {
    $start_date = $start_date;
    $end_date = $end_date;
    $result_items = array();

    $this->db->select('
      sales.sale_id AS sale_id,
      sales.sale_time AS sale_time,
      sales.customer_id AS customer_id,
      sales.tally_number AS tally_number,
      sales.employee_id AS employee_id,
      sales.sale_status AS sale_status,
      sales.sale_type AS sale_type,
      sales.bill_type AS bill_type,

      sales_items.item_id AS item_id,
      sales_items.quantity_purchased AS quantity,
      sales_items.item_unit_price AS item_price,
      sales_items.discount_percent AS item_discount
    ');

    $this->db->from('sales');
    $this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id');
    $this->db->where('DATE(sale_time) BETWEEN "'.rawurldecode($start_date).'" AND "'.rawurldecode($end_date).'"');
    $report_results = $this->db->get()->result_array();
    
    $filename = 'DBF POS'.date('d-m').'.csv';
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/csv; ");
  
    // file creation
    $file = fopen('php://output', 'w');
  

  $header = array( "Sale ID", "Sale Time","Customer Name","Customer GST No.", "Invoice Number","Shop ID",
  "Barcode","Item Name", "Item Category","Item Subcategory","Item Brand", "Taxable Value","HSN", "CGST %",
  "CGST Amt.","SGST %","SGST Amt.", "IGST %", "IGST Amt.", "Quantity", "Item Type","Discount", 
  "Gross Value", "Sale Payments","Sale Type","Sale Status","Customer Type","Stock Edition");
  fputcsv($file, $header);

    foreach ($report_results as $row)
    {
      $discounted_price = $row['item_price'] - bcmul($row['item_price'], bcdiv($row['item_discount'], 100));
      $tax_data = $this->Item_taxes->get_sales_tax($row['sale_id'], $row['item_id']);
  
  
      $total_tax = ((empty($tax_data['tax_percents']['CGST'])) ? 0.00 : $tax_data['tax_percents']['CGST']) + ((empty($tax_data['tax_percents']['SGST'])) ? 0.00 : $tax_data['tax_percents']['SGST']) + ((empty($tax_data['tax_percents']['IGST'])) ? 0.00 : $tax_data['tax_percents']['IGST']);
  
        $price = bcmul($discounted_price, $row['quantity']);
        $a = $price * $total_tax;
        $b = 100 + $total_tax;
        $taxable_value = $a / $b;
  
  $item_info = $this->Item->get_info($row['item_id']);
  $customer_info = $this->Customer->get_info($row['customer_id']);
  $sale_payments = $this->Sale->get_sale_payment_types($row['sale_id']);
  $sale_payment ='';
  foreach($sale_payments as $pays){
  $sale_payment .= $pays['payment_type']." ";
  } 
  fputcsv($file,array(
  $row['sale_id'],
  $row['sale_time'],
  $customer_info->first_name." ".$customer_info->last_name,
  $customer_info->gstin,
  $row['tally_number'],
  $this->Stock_location->get_location_name2($row['employee_id']),
  $item_info->item_number,
  $item_info->name,
  $item_info->category,
  $item_info->subcategory,
  $item_info->brand,
  $price - round($taxable_value, 2),
  $item_info->custom1,
  (empty($tax_data['tax_percents']['CGST'])) ? NULL : $tax_data['tax_percents']['CGST'],
  (empty($tax_data['tax_amounts']['CGST'])) ? NULL : $tax_data['tax_amounts']['CGST'],
  (empty($tax_data['tax_percents']['SGST'])) ? NULL : $tax_data['tax_percents']['SGST'],
  (empty($tax_data['tax_amounts']['SGST'])) ? NULL : $tax_data['tax_amounts']['SGST'],
  (empty($tax_data['tax_percents']['IGST'])) ? NULL : $tax_data['tax_percents']['IGST'],
  (empty($tax_data['tax_amounts']['IGST'])) ? NULL : $tax_data['tax_amounts']['IGST'],
  to_quantity_decimals($row['quantity']),
  ($item_info->unit_price == 0.00) ? "FP" : "DISC",
  $row['item_discount'],
  $price,
  $sale_payment,
  ($row['sale_type'] == 1) ? "Invoice" : "Credit Note",
  ($row['sale_status'] == 0) ? "Active" : "Cancelled",
  ($row['bill_type'] == 'ys') ? "Special Approval" : ucfirst($row['bill_type']),
  $item_info->custom6,
  ));
  }

    fclose($file);
    exit;
  
  }

  public function monthly_report()
  {
    $data['stock_locations'] = $this->Stock_location->get_allowed_locations();
    $this->load->view('manager/modals/monthly_report',$data);
  }

  public function monthly_sales_format()
  {
      $filters = array(
      'sale_type' => $this->input->post('sale_type'),
      'start_date' => $this->input->post('start_date'),
      'end_date' => $this->input->post('end_date'),
      'only_cash' => FALSE,
      'only_due' => FALSE,
      'only_check' => FALSE,
      'only_invoices' => FALSE,
      'employee_id'=> FALSE,
      'is_valid_receipt' => $this->Sale->is_valid_receipt($search)
    );
    
    $filters['location_id'] =  $this->input->post('location_id');
    $filledup = array_fill_keys(array(), TRUE);
    $filters = array_merge($filters, $filledup);

    $sales = $this->Sale->search(array(), $filters);
    $payments = $this->Sale->get_payments_summary(array(), $filters);
    $payment_summary = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales));

    $data_rows = array();
    foreach($sales->result() as $sale)
    {
      $data_rows[] = $this->xss_clean(get_sale_data_row1($sale));
    }
     $data['report'] = $data_rows;
    
     $this->load->view('manager/sublists/monthly_format', $data);
  }

  public function custom_report()
  {
      $data['stock_locations'] = $this->Stock_location->get_allowed_locations();
      $this->load->view('manager/modals/custom_report',$data);
  }
  
  public function custom_sales_format()
  {
      $filters = array(
      'sale_type' => $this->input->post('sale_type'),
      'start_date' => $this->input->post('start_date'),
      'end_date' => $this->input->post('end_date'),
      'only_cash' => FALSE,
      'only_due' => FALSE,
      'only_check' => FALSE,
      'only_invoices' => FALSE,
      'employee_id'=> FALSE,
      'is_valid_receipt' => $this->Sale->is_valid_receipt($search)
    );
    
    $filters['location_id'] =  $this->input->post('location_id');
    $filledup = array_fill_keys(array(), TRUE);
    $filters = array_merge($filters, $filledup);

    $sales = $this->Sale->search(array(), $filters);
    $payments = $this->Sale->get_payments_summary(array(), $filters);
    $payment_summary = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales));

    $data_rows = array();
    foreach($sales->result() as $sale)
    {
      $data_rows[] = $this->xss_clean(get_sale_data_row1($sale));
    }
     $data['report'] = $data_rows;
     $this->load->view('manager/sublists/custom_format', $data);
  }
  public function send_email(){
    $this->load->view('manager/modals/send_email');
  }

  public function monthly_report_csv(){

   
    $curr_month = date('m', strtotime("-1 month"));
    $curr_year = date('Y');

    $start_date =  $curr_year .'-' . $curr_month . '-' . '01';
    $end_date = date("Y-m-t", strtotime($start_date));
   

     $filters = array(
      'sale_type' => 'all',
      'start_date' => $start_date,
      'end_date' => $end_date,
      'only_cash' => FALSE,
      'only_due' => FALSE,
      'only_check' => FALSE,
      'only_invoices' => FALSE,
      'location_id'=>'all',
      'employee_id'=> FALSE,
      'is_valid_receipt' => $this->Sale->is_valid_receipt($search)
    );
    // $filters['location_id'] =  $this->input->get_post('location_id');
    $filledup = array_fill_keys(array(), TRUE);
    $filters = array_merge($filters, $filledup);

    $sales = $this->Sale->search(array(), $filters);

    $payments = $this->Sale->get_payments_summary(array(), $filters);
    $payment_summary = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales));

    $data_rows = array();
    foreach($sales->result() as $sale)
    {
      $data_rows[] = $this->xss_clean(get_sale_data_row1($sale));
    }
  
    $curr_month_name = date('M', strtotime("-1 month"));
   
    $filename = 'DBF_'.$curr_month_name.'_Report_Data'.'.csv';

	  // header("Content-Description: File Transfer");
	  // header("Content-Disposition: attachment; filename=".$filename);
	  // header("Content-Type: application/csv; ");
    

    //file creation and store 
   if($file = fopen("../reports/monthly_sales_report/".$filename, 'w+')){
    
   
	//	$file = fopen('php://temp', 'w');
	
    $header = array("ID", "Time", "Coustmer Name", "Tally Number", "Invoice Number", "Total Amount", "Payment Mode", "Sale Type", "Bill Type","Shop ID");
    fputcsv($file, $header);
   
     foreach($data_rows as $row){
      fputcsv($file,array(
           $row['sale_id'],
           $row['sale_time'],
           $row['customer_name'],
           $row['tally_number'],
           $row['invoice_number'],
           $row['amount_tendered'],
           $row['payment_type'],
           ($row['sale_type'] == 1) ? "Invoice" : "Credit Note",
           ($row['bill_type'] == 'ys') ? "Special Approval" : ucfirst($row['bill_type']), 
           $this->Stock_location->get_location_name2($row['employee_id']),
      ));           
    }
    echo '<script>
    alert("success");
    window.location.href="http://localhost/newpos-lives/public/manager/monthly_report";
    </script>
    ';

    fclose($file);
   

  } 
  else{
    echo '<script>alert("failed");</script>';
  }
       
  }

  public function fetch_stockup_items()
  {
    $result = $this->db->order_by('id',"desc")
    ->limit(1)
    ->get('upload_items')
    ->row();
    
    $data['items'] = json_decode($result->stock_ups);
    $data['upload_time'] = $result->time;

    $this->load->view('manager/sublists/uploadItems_sublist', $data);
  }

  public function fetch_new_items()
  {
    $result = $this->db->order_by('id',"desc")
    ->limit(1)
    ->get('upload_items')
    ->row();
    
    $data['items'] = json_decode($result->new_items);
    $data['upload_time'] = $result->time;

    $this->load->view('manager/sublists/uploadItems_sublist', $data);
  }

  public function get_mci_list()
  {
    $table = 'master_'.$this->input->post('type');
    $data['mci_data'] = $this->db->get($table)->result_array();
    $this->load->view('manager/sublists/mci_sublist', $data);
  }

  public function get_mci_sublist()
  {
    $parent_id = $this->input->post('parent_id');
    $data['mci_data'] = $this->db->where('parent_id', $parent_id)->get('master_subcategories')->result_array();
    $this->load->view('manager/sublists/mci_sublist', $data);
  }

  public function mci_save()  // create new cat,subcat,brand,size,color
	{
    $type = $this->input->post('type');
		$name = strtoupper(trim($this->input->post('name')));

		if($type == "subcategories")
		{
			$id = trim($this->input->post('id'));
			$parent_id = $this->input->post('parent_id');
			$data = array(
				'id' => $id,
				'name' => $name,
				'parent_id' => $parent_id
			);
		}
		else if($type == "categories")
		{
			$id = trim($this->input->post('id'));
			$data = array(
				'id' => $id,
				'name' => $name
			);
		}
		else
		{
			$data = array(
				'name' => $name,
			);
		}

    $tablename = 'master_'.$type;
    $count = $this->db->where('name', $name)->count_all_results($tablename);
    if($count == 0)
    {
      $this->db->insert($tablename, $data);
      echo "Successfully Created";
    }
    else
    {
			echo "Duplicate Entry Not Saved!";
		}
	}

	public function mci_update()  // update existing cat,subcat,brand,size,color
	{
		$name = strtoupper(trim($this->input->post('name')));
		$type = $this->input->post('type');
		$id = $this->input->post('id');

		if($type == "subcategories")
		{
			$parent_id = $this->input->post('parent_id');
			$data = array(
				'name' => $name,
				'parent_id' => $parent_id
			);
		}
		else
		{
			$data = array(
				'name' => $name,
			);
		}
		$this->db->where('id', $id);
		$tablename = 'master_'.$type;
		$this->db->update($tablename, $data);
		echo 'Successfully Updated';
  }

  public function cashier_add()
  {
    foreach($this->Pricing->get_active_shops(array('shop', 'dbf', 'hub')) as $row)
		{
			$shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
		}
		$data['shops'] = $shops;
    $this->load->view('manager/modals/cashier_add', $data);
  }

  public function cashier_save()
	{
    $sale_code = $this->input->post('sale_code');
    $contact = $this->input->post('contact');
    $count = $this->db->where('id', $sale_code)->count_all_results('cashiers');

    if($count == 0)
    {
      $data = array(
        'id' => $sale_code,
        'shops' => json_encode($this->input->post('shops')),
        'name' => $this->input->post('name'),
        'contact' => $contact
      );
  
      if($this->db->insert('cashiers', $data)){
        echo "Cashier Added";
      }
    }
    else
    {
      echo "Duplicate Sale Code";
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
  
  // public function get_incentive_report()
  // {
  //   $shop_id = $this->input->post('shop_id');
  //   $this->db->where('employee_id', $shop_id);

  // }
  public function select_location()
  {

    $this->load->view('manager/modals/select_location');
  }
  public function detail_stocklocation()
  {
    $location_name = $this->input->post('report_edit');
    $data['editadd'] = $this->db->select('location_id, location_name, shop_incharge, alias, address, tnc')->where('location_name',$location_name)->get('stock_locations')->result_array();
    $this->load->view('manager/sublists/detail_info_location',$data);
  }

   public function save_stocklocation()
      {
         $location_id = $this->input->post('location_id');
         $shop_incharge = $this->input->post('shop_incharge');
         $alias = $this->input->post('alias');
         $address = $this->input->post('address');
         $tnc = $this->input->post('tnc');
         $insert_data = array('shop_incharge'=>$shop_incharge, 'alias'=>$alias, 'address'=>$address, 'tnc'=>$tnc);

         $update_data = array('shop_incharge'=>$shop_incharge, 'alias'=>$alias, 'address'=>$address, 'tnc'=>$tnc);

      if(!empty($location_id))
      {
        $this->db->where('location_id',$location_id)->update('stock_locations',$update_data);
        echo json_encode($update_data);

      }
      else
      {
        $this->db->insert('stock_locations',$insert_data);
        echo json_encode($insert_data);
       
      }
    }

  public function edit_stocklocation($location_id)
  {
    $data['editadd'] = $this->db->select('location_id, location_name, shop_incharge, alias, address, tnc')->where('location_id',$location_id)->get('stock_locations')->result_array();
   $this->load->view('manager/sublists/edit_stocklocation',$data);
  }
  public function bulk_hsn_view()
	{
		$categories = array('' => $this->lang->line('items_none'));
		foreach($this->Item->get_mci_data('categories') as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}

		$data['categories'] = $categories;
		$this->load->view('manager/modals/bulk_hsn_form', $data);
	}

	public function bulk_hsn_update()
	{
		$count = 0;
		$category = $this->input->post('category');
		$subcategory = $this->input->post('subcategory');
    $hsn = $this->input->post('hsn');
    $input_tax = $this->input->post('tax');
		$tax_percents = array(
			'CGST' => $input_tax/2,
			'SGST' => $input_tax/2,
			'IGST' => $input_tax
		);

		$array = array( #q1-array
			'category' => $category,
			);

		if(!empty($subcategory))
		{
			$array['subcategory'] = $subcategory; // further narrow down
		}

		$this->db->where($array); #q1
		$items_query = $this->db->get('items');

		foreach($items_query->result_array() as $row)
		{
			$item_id = $row['item_id'];
			if(!empty($hsn))
			{
				$data = array( #q2-array
					'custom1' => $hsn
				);
				$this->db->where('item_id', $item_id);
				$this->db->update('items', $data); #q2
			}

			foreach($tax_percents as $key=>$value)
			{
				$count++;
				$array = array( #q3-array
					'item_id' => $item_id,
					'name' => $key
				);
				$this->db->where($array); #q3

				$data = array( #q4-array
					'percent' => $value
				);
				$this->db->update('items_taxes', $data); #q4
				if($this->db->affected_rows() != 1)
				{
          if($this->db->where($array)->count_all_results('items_taxes') != 1)
          {
            $data = array(
              'item_id' => $item_id,
              'name' => $key,
              'percent' => $value
            );
            $this->db->insert('items_taxes', $data);
          }
				}
			}
    }

    $log_data = array(
      'user_id' => $this->session->userdata('person_id'),
      'method' => 'bulk_hsn',
      'info' => json_encode(array(
                  'category' => $category,
                  'subcategory' => $subcategory,
                  'hsn' => $hsn,
                  'input_tax' => $input_tax
                )),
      'time' => date('Y-m-d H:i:s')          
    );
    $this->db->insert('bulk_actions', $log_data);

		echo $count.' Items successfully processed';
	}

	// Custom Bulk Discount Update Function
	public function bulk_discount_view()
	{
		$categories = array('' => $this->lang->line('items_none'));
		foreach($this->Item->get_mci_data('categories') as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$brands = array('' => $this->lang->line('items_none'));
		foreach($this->Item->get_mci_data('brands') as $row)
		{
			$brands[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		foreach($this->Item->get_custom_discounts() as $row)
		{
			$custom_discounts[$this->xss_clean($row['alias'])] = $this->xss_clean($row['title']);
		}

		$data['categories'] = $categories;
		$data['brands'] = $brands;
		$data['custom_discounts'] = $custom_discounts;
		$this->load->view('manager/modals/bulk_discount_form', $data);
	}

	public function bulk_discount_update()
	{
    $counter = 0;
		$radio = $this->input->post('radio');
		$key1 = $this->input->post('key1');
		$key2 = $this->input->post('key2');
		$dtype = $this->input->post('dtype');
		$dvalue = $this->input->post('dvalue');

		if($radio == "subcategory")
		{
      $array = array(
				'category' => $key1,
				'subcategory' => $key2,
				'deleted' => 0
			);
		}
		else
		{
      $array = array(
				$radio => $key1,
				'deleted' => 0
      );
		}
    $this->db->where($array);
    $items_array = $this->db->get('items')->result_array();
		foreach($items_array as $row)
		{
			$item_id = $row['item_id'];
			$discounts = json_decode($row['discounts']);
      $discounts->$dtype = number_format($dvalue, 2);
      $data = array(
        'discounts' => json_encode($discounts)
      );
			$this->db->where('item_id', $item_id);
      if($this->db->update('items', $data))
      {
        $counter++;
      }
    }

    $log_data = array(
      'user_id' => $this->session->userdata('person_id'),
      'method' => 'bulk_discount',
      'info' => json_encode(array(
                  'type' => $radio,
                  'category' => $key1,
                  'subcategory' => $key2,
                  'discount_type' => $dtype,
                  'discount_value' => $dvalue
                )),
      'time' => date('Y-m-d H:i:s')          
    );
    $this->db->insert('bulk_actions', $log_data);

		echo $counter.' Items successfully updated!';
  }

  public function quick_bulk_discount()
  {
    $this->load->view('manager/file_uploaders/discount_uploader', NULL);
  }

  public function do_quick_bulk_discount()
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

          // COLUMN1
          $barcode = $data[0]; 

          // COLUMN2
          switch ($data[1]) { 
              case 1:
                  $discount_type = 'retail';
                  break;
              case 2:
                  $discount_type = 'wholesale';
                  break;
              case 3:
                  $discount_type = 'franchise';
                  break;
              case 4:
                  $discount_type = 'ys';
                  break;    
              default:
                  // No Action
          }

          // COLUMN3
          $discount_value = $data[2];

          // COLUMN4
          if($data[3] == 1) // DISCOUNTED ITEMS  
          {
            $item_info = $this->Item->get_info_by_id_or_number($barcode);
            $discounts = json_decode($item_info->discounts);
            $discounts->$discount_type = number_format($discount_value, 2);
            $data = array(
              'discounts' => json_encode($discounts)
            );
            $this->db->where('item_number', $barcode)->update('items', $data);
          }
          else if($data[3] == 2) // FP ITEMS
          {
            $item_info = $this->Item->get_info_by_id_or_number($barcode);
            $discounts = json_decode($item_info->cost_price);
            $discounts->$discount_type = number_format($discount_value, 2);
            $data = array(
              'unit_price' => 0,
              'cost_price' => json_encode($discounts)
            );
            $this->db->where('item_number', $barcode)->update('items', $data);
          }

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
  
  public function bulk_action_report()
  {
    $method = $this->input->post('report_type'); 
    $data['bulk_actions'] = $this->db->where('method', $method)->get('bulk_actions')->result_array();
    $this->load->view('manager/sublists/bulk_action_sublist', $data);
  }

  public function quick_convert()
  {
    $this->load->view('manager/file_uploaders/excel_uploader', NULL);
  }

  public function do_quick_convert()
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
        $active_items = array();
        $deleted_items = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$barcode = $data[0];
					$location_id = $data[1];
					$location_quantity = $data[2];

          $where_array = array(
            'deleted' => 0,
            'item_number' => $barcode
          );

					$count = $this->db->where($where_array)->count_all_results('items');

					if($count == 1)
					{
            for($x = 1; $x <= $location_quantity; $x++)
            {
              $active_items[] = $barcode;
            }
					}
					else
					{
						$deleted_items[] = $barcode;
					}

					++$i;

				} // while loop ends here

				$extras_array = array(
					'active_items' => json_encode($active_items),
          'deleted_items' => json_encode($deleted_items),
          'time' => date('Y-m-d H:i:s')
				);
				$this->db->insert('extras', $extras_array);

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

  public function quick_taxes()
  {
    $this->load->view('manager/file_uploaders/excel_uploader1', NULL);
  }

  public function do_quick_taxes()
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
        $items_taxes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

          $sale_id = $data[0];
          $item_id = $data[1];
          $tax_percent = $data[2];
          $tax_amt = $data[3];

          $array1 = array(
            'sale_id' => $sale_id,
            'item_id' => $item_id
          );

          $array2 = array(
            'percent' => number_format($tax_percent,1),
            'item_tax_amount' => number_format($tax_amt,2)
          );

          $this->db->where($array1)->update('sales_items_taxes', $array2);

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

  // public function quick_prices()
  // {
  //   $this->load->view('manager/file_uploaders/excel_uploader2', NULL);
  // }

  public function do_quick_prices()
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
        $active_items = array();
        $deleted_items = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$barcode = $data[0];
					$location_id = $data[1];
					$location_quantity = $data[2];

					$count =$this->db->where('item_number', $barcode)->count_all_results('items');

					if($count == 1)
					{
            for($x = 1; $x <= $location_quantity; $x++)
            {
              $active_items[] = $barcode;
            }
					}
					else
					{
						$deleted_items[] = $barcode;
					}

					++$i;

				} // while loop ends here

				$extras_array = array(
					'active_items' => json_encode($active_items),
          'deleted_items' => json_encode($deleted_items),
          'time' => date('Y-m-d H:i:s')
				);
				$this->db->insert('extras', $extras_array);

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

  public function get_processed_list()
  {
    $type = $this->input->post('type');
    $data['items'] = $this->db->order_by('id',"desc")
    ->limit(1)
    ->get('extras')->row($type);

    $this->load->view('manager/sublists/excel_processed', $data);
  }

  //To fetch Uploaded Sheets acc to status(pending, approved or dscarded)
  public function items_upload($status='not_processed'){
    if($status=='not_processed'){

      $this->db->select('sheet_uploads.*,custom_fields.title');
      $this->db->join('custom_fields','sheet_uploads.sheet_uploader_id=custom_fields.id');
      $this->db->where('sheet_uploads.type','new_stock');
      $data['sheets'] = $this->db->get('sheet_uploads')->result();

      $data['sheetStatus']='not_processed';
      $url = 'manager/sublists/inventory/items_upload_sheet';
      $this->load->view($url, $data);

    }else{

      $this->db->select('sheet_uploads.*,custom_fields.title');
      $this->db->join('custom_fields','sheet_uploads.sheet_uploader_id=custom_fields.id');
      $this->db->where('sheet_uploads.type','new_stock');
      $data['sheets'] = $this->db->get_where('sheet_uploads',array('sheet_uploads.status'=>'approved'))->result();

      $data['sheetStatus']='processed';
      $url = 'manager/sublists/inventory/items_upload_sheet';
      $this->load->view($url, $data);
    }
  }
  //display uploaded sheet data
  public function items_upload_data($sheet_id=1){
    $this->db->select('sheet_uploads_data.*');
    $this->db->from('sheet_uploads_data');
    $this->db->select('sheet_uploads.name as sheet_name, sheet_uploads.status as sheet_status');
    $this->db->join('sheet_uploads','sheet_uploads_data.parent_id = sheet_uploads.id');
    $this->db->where('parent_id',$sheet_id); 
    $data['sheets'] = $this->db->get()->result();
    $this->load->view('manager/sublists/inventory/items_upload_data',$data);
  }
  public function items_processed_data($sheet_id=1){
    $data['sheets'] = $this->db->select()->get_where('sheet_processed_data',array('parent_id'=>$sheet_id))->result();
    $this->load->view('manager/sublists/inventory/items_processed_data',$data);
  }
    //users from custom field
  public function verify_user(){

    $pwd = $this->input->post('pwd');
    $this->db->select();
    if(empty($this->input->post('id'))){
      $this->db->where(array('tag'=>'sheet_uploader_admin'));
    }
    $database_password =  $this->db->get('custom_fields')->row()->varchar_value;

    if($database_password==$pwd){
      echo true;
    }
    else{
      echo 'Incorrect Password.';
    }
  }
}