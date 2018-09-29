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
    $data['cashiers'] = $this->db->get('cashiers')->result_array();
    foreach($this->Pricing->get_active_shops(array('dbf', 'shop', 'hub')) as $row)
		{
			$active_shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
		}
		$data['active_shops'] = $active_shops;
    $data['stock_locations'] = $this->Stock_location->get_allowed_locations();
    $data['mci_data'] = $this->Item->get_mci_data('all');
    $this->load->view('manager/dashboard', $data);
  }

  public function get_count()
  {
    $count = 0;
    $locations = $this->input->post('locations');

    $filter = $this->input->post('filter');
    foreach($filter as $key=>$value)
    {
      if(!empty($value)){
        $array[$key] = $value;
      }
    }

    $this->db->where('deleted', 0);
    $this->db->where($array);
    $filtered_items = $this->db->get('items')->result_array();

    foreach($locations as $location)
    {
      foreach($filtered_items as $row)
      {
        $this->db->where('location_id', $location);
        $this->db->where('item_id', $row['item_id']);
        $count += $this->db->get('item_quantities')->row()->quantity;
      }
    }

    echo $count;
  }

  public function count_all_items()
  {
    $count = 0;
    $locations = $this->input->post('locations');
    $this->db->where('deleted', 0);
    $items = $this->db->get('items')->result_array();


    foreach($locations as $location)
    {
      foreach($items as $row)
      {
        $this->db->where('location_id', $location);
        $this->db->where('item_id', $row['item_id']);
        $count += $this->db->get('item_quantities')->row()->quantity;
      }
    }
    
    echo $count;
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

  public function list_all_items()
  {
    $data['locations'] = $this->input->post('locations');
    $this->db->where('deleted', 0);
    $data['items'] = $this->db->get('items')->result_array();
    $this->load->view('manager/sublists/items_sublist', $data);
  }

  public function list_filtered_items()
  {
    $data['locations'] = $this->input->post('locations');

    $filter = $this->input->post('filter');
    foreach($filter as $key=>$value)
    {
      if(!empty($value)){
        $array[$key] = $value;
      }
    }

    $this->db->where('deleted', 0);
    $this->db->where($array);
    $data['items'] = $this->db->get('items')->result_array();
    $this->load->view('manager/sublists/items_sublist', $data);
  }

  public function report_sales()
  {
    // $data['locations'] = $this->input->post('locations');
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');

    $filter = $this->input->post('filter');

    if($filter == 'all')
    {
      $array = array();
    }
    else
    {
      foreach($filter as $key=>$value)
      {
        if(!empty($value)){
          $array[$key] = $value;
        }
      }
    }

    $this->db->where('deleted', 0);
    $this->db->where($array);
    $result = $this->db->get('items')->result_array();
    foreach($result as $row)
    {
      $result_items[] = $row['item_id'];
    }

    $this->db->select('
      sales.sale_time AS sale_time,
      sales.customer_id AS customer_id,
      sales.employee_id AS employee_id,
      sales.invoice_number AS invoice_number,
      sales.sale_id AS sale_id,
      sales_items.item_id AS item_id,
      sales_items.quantity_purchased AS quantity_purchased,
      sales_items.item_unit_price AS item_unit_price,
      sales_items.discount_percent AS discount_percent,
      sales_items.item_location AS item_location,
    ');
    $this->db->from('sales');
    $this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id');
    $this->db->where('sale_time >=', date('Y-m-d H:i:s', strtotime($start_date)));
    $this->db->where('sale_time <=', date('Y-m-d H:i:s', strtotime($end_date)));
    $this->db->where_in('item_id', $result_items);
    $data['report_results'] = $this->db->get()->result_array();
    $this->load->view('manager/sublists/report_sales', $data);
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
    $this->load->view('manager/cashier_add', $data);
  }

  public function cashier_save()
	{
    $sale_code = $this->input->post('sale_code');
    $count = $this->db->where('id', $sale_code)->count_all_results('cashiers');

    if($count == 0)
    {
      $data = array(
        'id' => $sale_code,
        'shops' => json_encode($this->input->post('shops')),
        'name' => $this->input->post('name')
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

  public function bulk_hsn_view()
	{
		$categories = array('' => $this->lang->line('items_none'));
		foreach($this->Item->get_mci_data('categories') as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}

		$data['categories'] = $categories;
		$this->load->view('manager/bulk_hsn_form', $data);
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
		$this->load->view('manager/bulk_discount_form', $data);
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
  
  public function bulk_action_report()
  {
    $method = $this->input->post('report_type'); 
    $data['bulk_actions'] = $this->db->where('method', $method)->get('bulk_actions')->result_array();
    $this->load->view('manager/sublists/bulk_action_sublist', $data);
  }

  public function excel_conversion()
  {
    $this->load->view('manager/modals/excel_uploader', NULL);
  }

  public function do_excel_conversion()
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

}