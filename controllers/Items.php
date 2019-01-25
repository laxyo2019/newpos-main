<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Items extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('items');

		$this->load->library('item_lib');
	}

	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_items_manage_table_headers());

		 $data['stock_location'] = $this->xss_clean($this->item_lib->get_item_location());
		 $data['stock_locations'] = $this->xss_clean($this->Stock_location->get_allowed_locations());

		// filters that will be loaded in the multiselect dropdown
		$data['filters'] = array('empty_upc' => $this->lang->line('items_empty_upc_items'),
			'low_inventory' => $this->lang->line('items_low_inventory_items'),
			'is_serialized' => $this->lang->line('items_serialized_items'),
			'no_description' => $this->lang->line('items_no_description_items'),
			'search_custom' => $this->lang->line('items_search_custom_items'),
			'is_deleted' => $this->lang->line('items_is_deleted'));

		$this->load->view('items/manage', $data);
	}

	/*
	Returns Items table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$this->item_lib->set_item_location($this->input->get('stock_location'));

		$filters = array(
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'),
			'stock_location_id' => $this->item_lib->get_item_location(),
			'empty_upc' => FALSE,
			'low_inventory' => FALSE,
			'is_serialized' => FALSE,
			'no_description' => FALSE,
			'search_custom' => FALSE,
			'is_deleted' => FALSE
		);

		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);

		$items = $this->Item->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Item->get_found_rows($search, $filters);

		$data_rows = array();
		foreach($items->result() as $item)
		{
			// $data_rows[] = $this->xss_clean(get_item_data_row($item));
			$data_rows[] = get_item_data_row($item);
			// if($item->pic_filename!='')
			// {
			// 	$this->_update_pic_filename($item);
			// }
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	public function pic_thumb($pic_filename)
	{
		$this->load->helper('file');
		$this->load->library('image_lib');

		// in this context, $pic_filename always has .ext
		$ext = pathinfo($pic_filename, PATHINFO_EXTENSION);
		$images = glob('./uploads/item_pics/' . $pic_filename);

		// make sure we pick only the file name, without extension
		$base_path = './uploads/item_pics/' . pathinfo($pic_filename, PATHINFO_FILENAME);
		if(sizeof($images) > 0)
		{
			$image_path = $images[0];
			$thumb_path = $base_path . $this->image_lib->thumb_marker . '.' . $ext;
			if(sizeof($images) < 2)
			{
				$config['image_library'] = 'gd2';
				$config['source_image']  = $image_path;
				$config['maintain_ratio'] = TRUE;
				$config['create_thumb'] = TRUE;
				$config['width'] = 52;
				$config['height'] = 32;
				$this->image_lib->initialize($config);
				$image = $this->image_lib->resize();
				$thumb_path = $this->image_lib->full_dst_path;
			}
			$this->output->set_content_type(get_mime_by_extension($thumb_path));
			$this->output->set_output(file_get_contents($thumb_path));
		}
	}

	public function get_subcustomdata($category)
	{
		$this->db->where('name', $category);
		$query = $this->db->get('master_categories');
		$parent_id = $query->row('id');

		$this->db->where('parent_id', $parent_id);
		return $this->db->get('master_subcategories');
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest_search()
	{
		$suggestions = $this->xss_clean($this->Item->get_search_suggestions($this->input->post_get('term'),
			array('search_custom' => $this->input->post('search_custom'), 'is_deleted' => $this->input->post('is_deleted') != NULL), FALSE));

		echo json_encode($suggestions);
	}

	public function suggest()
	{
		$suggestions = $this->xss_clean($this->Item->get_search_suggestions($this->input->post_get('term'),
			array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE));

		echo json_encode($suggestions);
	}

	public function suggest_kits()
	{
		$suggestions = $this->xss_clean($this->Item->get_kit_search_suggestions($this->input->post_get('term'),
			array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE));

		echo json_encode($suggestions);
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest_category()
	{
		$suggestions = $this->xss_clean($this->Item->get_category_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}

	/*
	 Gives search suggestions based on what is being searched for
	*/
	public function suggest_location()
	{
		$suggestions = $this->xss_clean($this->Item->get_location_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}

	/*
	 Gives search suggestions based on what is being searched for
	*/
	public function suggest_custom()
	{
		$suggestions = $this->xss_clean($this->Item->get_custom_suggestions($this->input->post('term'), $this->input->post('field_no')));

		echo json_encode($suggestions);
	}

	public function get_row($item_ids)
	{
		$item_infos = $this->Item->get_multiple_info(explode(":", $item_ids), $this->item_lib->get_item_location());

		$result = array();
		foreach($item_infos->result() as $item_info)
		{
			$result[$item_info->item_id] = $this->xss_clean(get_item_data_row($item_info));
		}

		echo json_encode($result);
	}

	// public function custom_script()
	// {
	// 	$str = '32A,
	// 	32B,
	// 	32C,
	// 	32D,
	// 	32DD,
	// 	32E,
	// 	34A,
	// 	34B,
	// 	34C,
	// 	34D ,
	// 	34DD,
	// 	34E,
	// 	36A,
	// 	36B,
	// 	36C,
	// 	36D,
	// 	36DD,
	// 	36E,
	// 	36F,
	// 	38B,
	// 	38D ,
	// 	38DD,
	// 	38E,
	// 	38F,
	// 	40B';
	// 	$array = explode(',', $str);
	// 	$count = 0;
	// 	foreach($array as $row)
	// 	{
	// 		$data = array(
	// 			'name' => strtoupper(trim($row))
	// 		);
	// 		$query = $this->db->insert('master_sizes', $data);
	// 		if($query)
	// 		{
	// 			echo 'Successfully Created';
	// 			echo '<br>';
	// 			$count++;
	// 		}
	// 	}
	// 	echo $count;
	// }

	public function view($item_id = -1) #view-method
	{
		$data['item_tax_info'] = $this->xss_clean($this->Item_taxes->get_taxes_for_item_form($item_id));
		$data['item_kits_enabled'] = $this->Employee->has_grant('item_kits', $this->Employee->get_logged_in_employee_info()->person_id);
		$mci_data = $this->Item->get_mci_data('all');

		$item_info = $this->Item->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}

		if($item_id == -1)
		{
			$item_info->receiving_quantity = 1;
			$item_info->reorder_level = 1;
			$item_info->item_type = ITEM; // standard
			$item_info->stock_type = HAS_STOCK;
			$item_info->tax_category_id = 1;  // Standard
		}

		$data['item_info'] = $item_info;

		$categories = array('' => $this->lang->line('items_none'));
		foreach($mci_data['categories'] as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['categories'] = $categories;


		if($item_id == -1){
			$subcategories = array('' => $this->lang->line('items_none'));
			foreach($mci_data['subcategories'] as $row)
			{
				$subcategories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
			}
			$data['subcategories'] = $subcategories;
		}else{
			foreach($this->get_subcustomdata($item_info->category)->result_array() as $row)
			{
				$subcategories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
			}
			$data['subcategories'] = $subcategories;
		}


		$brands = array('' => $this->lang->line('items_none'));
		foreach($mci_data['brands'] as $row)
		{
			$brands[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['brands'] = $brands;

		$sizes = array('' => $this->lang->line('items_none'));
		foreach($mci_data['sizes'] as $row)
		{
			$sizes[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['sizes'] = $sizes;

		$colors = array('' => $this->lang->line('items_none'));
		foreach($mci_data['colors'] as $row)
		{
			$colors[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['colors'] = $colors;

		$data['selected_category'] = $item_info->category;
		$data['selected_subcategory'] = $item_info->subcategory;
		$data['selected_brand'] = $item_info->brand;
		$data['selected_size'] = $item_info->custom2;
		$data['selected_color'] = $item_info->custom3;

		// --------------------------------------------------------------

		$customer_sales_tax_support = $this->config->item('customer_sales_tax_support');
		if($customer_sales_tax_support == '1')
		{
			$data['customer_sales_tax_enabled'] = TRUE;
			$tax_categories = array();
			foreach($this->Tax->get_all_tax_categories()->result_array() as $row)
			{
				$tax_categories[$this->xss_clean($row['tax_category_id'])] = $this->xss_clean($row['tax_category']);
			}
			$data['tax_categories'] = $tax_categories;
			$data['selected_tax_category'] = $item_info->tax_category_id;
		}
		else
		{
			$data['customer_sales_tax_enabled'] = FALSE;
			$data['tax_categories'] = array();
			$data['selected_tax_category'] = '';
		}

		$stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
		foreach($stock_locations as $location)
		{
			$location = $this->xss_clean($location);

			$quantity = $this->xss_clean($this->Item_quantity->get_item_quantity($item_id, $location['location_id'])->quantity);
			$quantity = ($item_id == -1) ? 0 : $quantity;
			$location_array[$location['location_id']] = array('location_name' => $location['location_name'], 'quantity' => $quantity);
			$data['stock_locations'] = $location_array;
		}

		$data['custom_discounts'] = $this->Item->get_custom_discounts();

		$this->load->view('items/form', $data);
	}

	public function barcode_table()
	{
		$categories = array('' => 'Select Category', 'all' => 'ALL ITEMS');
		foreach($this->Item->get_mci_data('categories') as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['categories'] = $categories;

		$this->db->from('items');
		// $this->db->join('item_quantities as item_quantities', 'item_quantities.item_id = items.item_id');
		// $this->db->where('location_id', $this->session->userdata('item_location'));
		$this->db->where('deleted', 0);
		$this->db->order_by('items.item_id', 'desc');
		$this->db->limit(500);


		$data['items'] = $this->db->get()->result_array();

		$this->load->view('items/barcode_table', $data);
	}

	public function custom_items_filter()
	{
		$mci_type = $this->input->post('mci_type');
		$mci_value = $this->input->post('mci_value');

		// $this->db->join('item_quantities as item_quantities', 'item_quantities.item_id = items.item_id');

		$this->db->where('deleted', 0);

		if($mci_value != 'all')
		{
			$this->db->where($mci_type, $mci_value);
		}

		// $this->db->order_by('item_id', 'desc');
		$query = $this->db->get('items');

		if($query)
		{
			$data['items'] = $query->result_array();
			$this->load->view('items/barcode_sublist', $data);
		}
		else
		{
			$data['items'] = 'No Data Found!';
		}
	}

	public function ajax_fetch_subcategories()
	{
		$category = $this->input->post('category');
		if(!empty($category))
		{
			$parent_id = $this->db->where('name', $category)->get('master_categories')->row()->id;
			$sub_array = $this->db->where('parent_id', $parent_id)->get('master_subcategories')->result_array();

			echo '<option value="">Select Subcategory</option>';
			foreach($sub_array as $row)
			{
				echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
			}
		}
		else if($this->input->post('parent_id'))
		{
			$sub_array = $this->db->where('parent_id', $this->input->post('parent_id'))->get('master_subcategories')->result_array();
			foreach($sub_array as $row)
			{
				$subcategories[$this->xss_clean($row['id'])] = $this->xss_clean($row['name']);
			}
			$data['subcategories'] = $subcategories;
			$this->load->view('items/sub_filter_list', $data);
		}
	}

	public function inventory($item_id = -1)
	{
		$item_info = $this->Item->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$data['stock_locations'] = array();
		$stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
		foreach($stock_locations as $location)
		{
			$location = $this->xss_clean($location);
			$quantity = $this->xss_clean($this->Item_quantity->get_item_quantity($item_id, $location['location_id'])->quantity);

			$data['stock_locations'][$location['location_id']] = $location['location_name'];
			$data['item_quantities'][$location['location_id']] = $quantity;
		}

		$this->load->view('items/form_inventory', $data);
	}

	public function count_details($item_id = -1)
	{
		$item_info = $this->Item->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$data['stock_locations'] = array();
		$stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
		foreach($stock_locations as $location)
		{
			$location = $this->xss_clean($location);
			$quantity = $this->xss_clean($this->Item_quantity->get_item_quantity($item_id, $location['location_id'])->quantity);

			$data['stock_locations'][$location['location_id']] = $location['location_name'];
			$data['item_quantities'][$location['location_id']] = $quantity;
		}

		$this->load->view('items/form_count_details', $data);
	}

	public function generate_barcodes($item_ids)
	{
		$this->load->library('barcode_lib');

		$item_ids = explode(':', $item_ids);
		$result = $this->Item->get_multiple_info($item_ids, $this->item_lib->get_item_location())->result_array();
		$config = $this->barcode_lib->get_barcode_config();

		$data['barcode_config'] = $config;

		// check the list of items to see if any item_number field is empty
		foreach($result as &$item)
		{
			$item = $this->xss_clean($item);

			// update the barcode field if empty / NULL with the newly generated barcode
			if($this->config->item('barcode_generate_if_empty'))
			{
				$save_item = array('item_number' => $this->Item->barcode_factory($item['item_id']) );
				// update the item in the database in order to save the barcode field
				$this->Item->save($save_item, $item['item_id']);
			}
		}
		$data['items'] = $result;

		// display barcodes
		$this->load->view('barcodes/barcode_sheet', $data);
	}

	public function bulk_edit()
	{
		$mci_data = $this->Item->get_mci_data('all');
		$categories = array('' => $this->lang->line('items_none'));
		foreach($mci_data['categories'] as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['categories'] = $categories;
		$data['selected_category'] = $item_info->category;

		$brands = array('' => $this->lang->line('items_none'));
		foreach($mci_data['brands'] as $row)
		{
			$brands[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['brands'] = $brands;
		$data['selected_brand'] = $item_info->brand;

		$sizes = array('' => $this->lang->line('items_none'));
		foreach($mci_data['sizes'] as $row)
		{
			$sizes[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['sizes'] = $sizes;
		$data['selected_brand'] = $item_info->custom2;

		$colors = array('' => $this->lang->line('items_none'));
		foreach($mci_data['colors'] as $row)
		{
			$colors[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['colors'] = $colors;
		$data['selected_brand'] = $item_info->custom3;

		$this->load->view('items/form_bulk', $data);
	}

	public function display_discounts($item_id){
		$item = $this->Item->get_info($item_id);

		$data['item_data_type'] = ($item->unit_price < 1) ? 'FIXED PRICE' : 'DISCOUNTED';
		$data['item_data'] = ($item->unit_price < 1) ? $item->cost_price : $item->discounts;
		$data['item_name'] = $item->name;
		$data['barcode'] = $item->item_number;
		$data['pointer'] = $item->custom5;

		$this->load->view('items/discount_tooltip', $data);
	}

	public function save($item_id = -1) // #save-method for both insert and update
	{
		// ---------------------------------------------- Discounts JSON processing
		$discounts = array();
		$retail = $this->input->post('retail');
		$wholesale = $this->input->post('wholesale');
		$franchise = $this->input->post('franchise');
		$special = $this->input->post('special');

		$discounts['retail'] = $retail == NULL ? '0.00' : number_format($retail, 2, '.', '');
		$discounts['wholesale'] = $wholesale == NULL ? '0.00' : number_format($wholesale, 2, '.', '');
		$discounts['franchise'] = $franchise == NULL ? '0.00' : number_format($franchise, 2, '.', '');
		$discounts['ys'] = $special == NULL ? '0.00' : number_format($special, 2, '.', '');
		// ---------------------------------------------------------

		if(!empty($wholesale) && !empty($franchise))
		{
			if($this->input->post('unit_price') != 0.00)
			{
				$d5rule = (($franchise - $wholesale) >= 0) ? FALSE : TRUE;
			}
		}
		
		//Save item data
		$item_data = array(
			'name' => strtoupper($this->input->post('name')),
			'description' => $this->input->post('description'),
			'category' => strtoupper($this->input->post('category')),
			'subcategory' => strtoupper($this->input->post('subcategory')),
			'brand' => strtoupper($this->input->post('brand')),
			'item_type' => $this->input->post('item_type') == NULL ? ITEM : $this->input->post('item_type'),
			'stock_type' => $this->input->post('stock_type') == NULL ? HAS_STOCK : $this->input->post('stock_type'),
			'supplier_id' => $this->input->post('supplier_id') == '' ? NULL : $this->input->post('supplier_id'),
			'unit_price' => parse_decimals($this->input->post('unit_price')),
			'reorder_level' => parse_decimals($this->input->post('reorder_level')),
			'receiving_quantity' => parse_decimals($this->input->post('receiving_quantity')),
			'allow_alt_description' => $this->input->post('allow_alt_description') != NULL,
			'is_serialized' => $this->input->post('is_serialized') != NULL,
			'deleted' => $this->input->post('is_deleted') != NULL,
			'custom1' => $this->input->post('custom1') == NULL ? $this->Item->hsn_factory(strtoupper($this->input->post('subcategory'))) : $this->input->post('custom1'),
			'custom2' => $this->input->post('custom2') == NULL ? '' : $this->input->post('custom2'),
			'custom3' => $this->input->post('custom3') == NULL ? '' : $this->input->post('custom3'),
			'custom4' => $this->input->post('custom4') == NULL ? '' : $this->input->post('custom4'),
			'custom5' => $this->input->post('custom5') == NULL ? '' : $this->input->post('custom5'),
			// 'custom6' => $this->input->post('custom6') == NULL ? '' : $this->input->post('custom6'),
			// 'custom7' => $this->input->post('custom7') == NULL ? '' : $this->input->post('custom7'),
			// 'custom8' => $this->input->post('custom8') == NULL ? '' : $this->input->post('custom8'),
			// 'custom9' => $this->input->post('custom9') == NULL ? '' : $this->input->post('custom9'),
			// 'custom10' => $this->input->post('custom10') == NULL ? '' : $this->input->post('custom10')
		);

		if($item_data['unit_price'] == 0.00)
		{
			$item_data['cost_price'] = json_encode($discounts);
		}
		else
		{
			$item_data['discounts'] = json_encode($discounts);
		}

		// $x = $this->input->post('tax_category_id');
		// if(!isset($x))
		// {
		// 	$item_data['tax_category_id'] = '';
		// }
		// else
		// {
		// 	$item_data['tax_category_id'] = $this->input->post('tax_category_id');
		// }

		// if(!empty($upload_data['orig_name']))
		// {
		// 	// XSS file image sanity check
		// 	if($this->xss_clean($upload_data['raw_name'], TRUE) === TRUE)
		// 	{
		// 		$item_data['pic_filename'] = $upload_data['raw_name'];
		// 	}
		// }

		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);

		// if($this->Item->save($item_data, $item_id)){
		// 	echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
		// }

		$redundancy_count = $this->get_redundant_item($item_data, "count");
		if($redundancy_count > 1)
		{
			$message = $this->xss_clean(' ||item already exists|| ' . $item_data['name']);

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
		else if($d5rule)
		{
			$message = $this->xss_clean(' ||unauthorized discount rates|| ' . $item_data['name']);

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
		else
		{
			if($this->Item->save($item_data, $item_id))
			{
				$success &= TRUE;
				$new_item = FALSE;
				//New item
				if($item_id == -1)
				{
					$item_id = $item_data['item_id'];
					$new_item = TRUE;
				}

				// AUTOMATED BARCODING
				$item_barcode = array('item_number' => $this->Item->barcode_factory($item_id));
				$this->db->where('item_id', $item_id)->update('items', $item_barcode);

				// Allows more than 2 taxes to be set on the item using [] on form input name
				$items_taxes_data = array();
				$tax_names = $this->input->post('tax_names');
				$tax_percents = $this->input->post('tax_percents');

				$count = count($tax_percents);
				for ($k = 0; $k < $count; ++$k)
				{
					$tax_percentage = parse_decimals($tax_percents[$k]);
					if(is_numeric($tax_percentage))
					{
						$items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percentage);
					}
				}

				if(empty($items_taxes_data)){
					$items_taxes_data = $this->Item->tax_factory($item_id);
				}
				
				$success &= $this->Item_taxes->save($items_taxes_data, $item_id);

				//Save item quantity
				$stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
				foreach($stock_locations as $location)
				{
					$updated_quantity = parse_decimals($this->input->post('quantity_' . $location['location_id']));
					$location_detail = array('item_id' => $item_id,
											'location_id' => $location['location_id'],
											'quantity' => $updated_quantity);
					$item_quantity = $this->Item_quantity->get_item_quantity($item_id, $location['location_id']);
					if($item_quantity->quantity != $updated_quantity || $new_item)
					{
						$success &= $this->Item_quantity->save($location_detail, $item_id, $location['location_id']);

						$inv_data = array(
							'trans_date' => date('Y-m-d H:i:s'),
							'trans_items' => $item_id,
							'trans_user' => $employee_id,
							'trans_location' => $location['location_id'],
							'trans_comment' => $this->lang->line('items_manually_editing_of_quantity'),
							'trans_inventory' => $updated_quantity - $item_quantity->quantity
						);

						$success &= $this->Inventory->insert($inv_data);
					}
				}

				// if($success)
				// {
					$message = $this->xss_clean($this->lang->line('items_successful_' . ($new_item ? 'adding' : 'updating')) . ' ' . $item_data['name']);

					echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
				// }
				// else
				// {
					// $message = $this->xss_clean($upload_success ? $this->lang->line('items_error_adding_updating') . ' ' . $item_data['name'] : strip_tags($this->upload->display_errors()));
					// // $message = "Image Error";

					// echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
				// }
			}
			else // failure
			{
				$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $item_data['name']);

				echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
			}
		}
	}

	public function quick_item_quantity_update()
	{
		$item_id = $this->input->post('item_id');
		$new_quantity = $this->input->post('new_qty');
		$location_id = $this->session->userdata('item_location');
		$employee_id = $this->session->userdata('person_id');

		$location_detail = array(
			'item_id' => $item_id,
			'location_id' => $location_id,
			'quantity' => $new_quantity
		);
		$old_quantity = $this->Item_quantity->get_item_quantity($item_id, $location_id)->quantity;
		if($old_quantity != $new_quantity){
			if($this->Item_quantity->save($location_detail, $item_id, $location_id)){
				$inv_data = array(
					'trans_date' => date('Y-m-d H:i:s'),
					'trans_items' => $item_id,
					'trans_user' => $employee_id,
					'trans_location' => $location_id,
					'trans_comment' => $this->lang->line('items_manually_editing_of_quantity'),
					'trans_inventory' => $new_quantity - $old_quantity
				);

				$this->Inventory->insert($inv_data);
				echo "Successfully Updated";
			}else{
				echo "Server Error";
			}
		}else{
			echo "Same Value : Not Updated";
		}
	 }

	public function request_item_show()
	{
		$shop_id = $this->session->userdata('person_id');
		$data['items'] = $this->db->where('requester', $shop_id)
															->where('status', 0)
															->get('item_requests')
															->result_array(); 
		$this->load->view('items/my_item_requests', $data);
	}
	 
	public function request_item_add()
	{
		$item_id = $this->input->post('item_id');
		$request_qty = $this->input->post('request_qty');
		$employee_id = $this->session->userdata('person_id');

		$request_detail = array(
			'item_id' => $item_id,
			'barcode' => $this->Item->get_info($item_id)->item_number,
			'quantity' => $request_qty,
			'requester' => $employee_id,
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('item_requests', $request_detail);
		echo "Request Success";
	 }
	 
	public function request_item_cancel()
	{
		$id = $this->input->post('id');
		$this->db->delete('item_requests', array('id' => $id));
		echo "Request Cancelled";
	}

	public function request_item_accept()
	{
		$id = $this->input->post('id');
		$this->db->where('id', $id)->update('item_requests', array('status' => 1));
		echo "Request Accepted";
	}

	public function request_item_decline()
	{
		$id = $this->input->post('id');
		$this->db->where('id', $id)->update('item_requests', array('status' => 2));
		echo "Request Declined";
	}

	public function request_deck()
	{
		$data['items'] = $this->db->get('item_requests')->result_array();
		$this->load->view('items/requests_deck', $data);
	}

	public function switch_deck()
	{
		$shop_id = $this->input->post('shop_id');

		$data['items'] = (empty($shop_id)) ? $this->db->get('item_requests')->result_array() : $this->db->where('requester', $shop_id)->get('item_requests')->result_array();
	
		$this->load->view('items/sublists/deck_sublist', $data);
	}


	// CODE FOR CUSTOM SAVE FUNCTIONS ENDS

	public function check_item_number()
	{
		$exists = $this->Item->item_number_exists($this->input->post('item_number'), $this->input->post('item_id'));
		echo !$exists ? 'true' : 'false';
	}

	/*
	If adding a new item check to see if an item kit with the same name as the item already exists.
	*/
	public function check_kit_exists()
	{
		if($this->input->post('item_number') === -1)
		{
			$exists = $this->Item_kit->item_kit_exists_for_name($this->input->post('name'));
		}
		else
		{
			$exists = FALSE;
		}
		echo !$exists ? 'true' : 'false';
	}

	private function _handle_image_upload()
	{
		/* Let files be uploaded with their original name */

		// load upload library
		$config = array('upload_path' => './uploads/item_pics/',
			'allowed_types' => 'gif|jpg|png',
			'max_size' => '100',
			'max_width' => '640',
			'max_height' => '480'
		);
		$this->load->library('upload', $config);
		$this->upload->do_upload('item_image');

		return strlen($this->upload->display_errors()) == 0 || !strcmp($this->upload->display_errors(), '<p>'.$this->lang->line('upload_no_file_selected').'</p>');
	}

	public function remove_logo($item_id)
	{
		$item_data = array('pic_filename' => NULL);
		$result = $this->Item->save($item_data, $item_id);

		echo json_encode(array('success' => $result));
	}

	public function save_inventory($item_id = -1)
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		$location_id = $this->input->post('stock_location');
		$inv_data = array(
			'trans_date' => date('Y-m-d H:i:s'),
			'trans_items' => $item_id,
			'trans_user' => $employee_id,
			'trans_location' => $location_id,
			'trans_comment' => $this->input->post('trans_comment'),
			'trans_inventory' => parse_decimals($this->input->post('newquantity'))
		);

		$this->Inventory->insert($inv_data);

		//Update stock quantity
		$item_quantity = $this->Item_quantity->get_item_quantity($item_id, $location_id);
		$item_quantity_data = array(
			'item_id' => $item_id,
			'location_id' => $location_id,
			'quantity' => $item_quantity->quantity + parse_decimals($this->input->post('newquantity'))
		);

		if($this->Item_quantity->save($item_quantity_data, $item_id, $location_id))
		{
			$message = $this->xss_clean($this->lang->line('items_successful_updating') . ' ' . $cur_item_info->name);

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
		}
		else//failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $cur_item_info->name);

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}

	public function bulk_update()
	{
		$items_to_update = $this->input->post('item_ids');
		$item_data = array();

		foreach($_POST as $key => $value)
		{
			if($value != '' && !(in_array($key, array('item_ids', 'tax_names', 'tax_percents'))))
			{
				$item_data["$key"] = $value;
			}
		}

		if($this->Item->update_multiple($item_data, $items_to_update)){
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_successful_bulk_edit'), 'id' => $this->xss_clean($items_to_update)));
		}

		//Item data could be empty if tax information is being updated
		// if(empty($item_data) || $this->Item->update_multiple($item_data, $items_to_update))
		// {
		// 	$items_taxes_data = array();
		// 	$tax_names = $this->input->post('tax_names');
		// 	$tax_percents = $this->input->post('tax_percents');
		// 	$tax_updated = FALSE;
		// 	$count = count($tax_percents);
		// 	for ($k = 0; $k < $count; ++$k)
		// 	{
		// 		if(!empty($tax_names[$k]) && is_numeric($tax_percents[$k]))
		// 		{
		// 			$tax_updated = TRUE;

		// 			$items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k]);
		// 		}
		// 	}

		// 	if($tax_updated)
		// 	{
		// 		$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update);
		// 	}

		// 	echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_successful_bulk_edit'), 'id' => $this->xss_clean($items_to_update)));
		// }
		// else
		// {
		// 	echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_error_updating_multiple')));
		// }
	}

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->Item->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}

	public function get_redundant_item($item_data, $type)
	{
		$array = array(
			'name' => $item_data['name'],
			'category' => $item_data['category'],
			'subcategory' => $item_data['subcategory'],
			'brand' => $item_data['brand'],
			'custom2' => $item_data['custom2'], //size
			'custom3' => $item_data['custom3'], //color
			'deleted' => 0
		);

		if($item_data['unit_price'] < 1)
		{
			$array['cost_price'] = $item_data['cost_price'];
		}
		else
		{
			$array['unit_price'] = $item_data['unit_price'];
		}

		$this->db->from('items');
		$this->db->where($array);
		return ($type == "count") ? $this->db->count_all_results() : $this->db->get()->result_array();
	}

	/*
	Items import from excel spreadsheet
	*/
	public function excel()
	{
		$name = 'import_items.csv';
		$data = file_get_contents('../' . $name);
		force_download($name, $data);
	}

	public function excel_import()
	{
		$this->load->view('items/form_excel_import', NULL);
	}

	public function do_excel_import() #excel-import
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
				$stock_up_items = array();
				$new_sheet_items = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$discounts = array(
						'retail' => $data[9] == NULL ? '0.00' : number_format($data[9], 2, '.', ''),
						'wholesale' => $data[10] == NULL ? '0.00' : number_format($data[10], 2, '.', ''),
						'franchise' => $data[11] == NULL ? '0.00' : number_format($data[11], 2, '.', ''),
						'ys' => $data[12] == NULL ? '0.00' : number_format($data[12], 2, '.', ''),
					);

					$fixed_prices = array(
						'retail' => $data[13] == NULL ? '0.00' : number_format($data[13], 2, '.', ''),
						'wholesale' => $data[14] == NULL ? '0.00' : number_format($data[14], 2, '.', ''),
						'franchise' => $data[15] == NULL ? '0.00' : number_format($data[15], 2, '.', ''),
						'ys' => $data[16] == NULL ? '0.00' : number_format($data[16], 2, '.', ''),
					);

					/* haven't touched this so old templates will work, or so I guess... */
					if(sizeof($data) >= 24)
					{
						$item_data = array(
							'name'					=> strtoupper(trim($data[0])),
							'category'			=> strtoupper(trim($data[2])),
							'subcategory'		=> strtoupper(trim($data[3])),
							'brand'				  => strtoupper(trim($data[4])),
							'discounts'			=> json_encode($discounts),	// Discounts (JSON)
							'cost_price'		=> json_encode($fixed_prices), // Fixed Prices (JSON)
							'unit_price'		=> $data[5],
							'reorder_level'	=> $data[24],
							'description'		=> $data[23],
							// 'supplier_id'			=> $this->Supplier->exists($data[3]) ? $data[3] : NULL,
							// 'allow_alt_description'	=> $data[12] != '' ? '1' : '0',
							// 'is_serialized'			=> $data[13] != '' ? '1' : '0',
							'custom1'				=> ($data[1] == NULL) ? $this->Item->hsn_factory(strtoupper(trim($data[3]))) : $data[1], // HSN Code
							'custom2'				=> strtoupper(trim($data[22])), // Size
							'custom3'				=> strtoupper(trim($data[21])), // Color
							'custom4'				=> strtoupper(trim($data[20])),	// Model
							'custom5'				=> strtoupper(trim($data[19])), // Pointer
							// 'custom6'				=> $data[18],
							// 'custom7'				=> $data[20],
							// 'custom8'				=> $data[21],
							// 'custom9'				=> $data[22],
							// 'custom10'				=> $data[23]
						);
					}

					$redundancy_count = $this->get_redundant_item($item_data, "count");
					$redundant_item = $this->get_redundant_item($item_data, "get");

					if($redundancy_count == 1)
					{
						$item_id = $redundant_item[0]['item_id'];
						$location_id = $data[25];

						$new_quantity = $this->Item_quantity->get_item_quantity($item_id, $location_id)->quantity;
						$new_quantity += $data[26];

						$location_detail = array(
							'item_id' => $item_id,
							'location_id' => $location_id,
							'quantity' => $new_quantity
						);

						if($this->Item_quantity->save($location_detail, $item_id, $location_id))
						{
							$inv_data = array(
								'trans_date' => date('Y-m-d H:i:s'),
								'trans_items' => $item_id,
								'trans_user' => $this->session->userdata('person_id'),
								'trans_location' => $location_id,
								'trans_comment' => 'Qty CSV Imported',
								'trans_inventory' => $data[26]
							);

							$this->Inventory->insert($inv_data);
						}

						$su_data = $this->Item->get_info($redundant_item[0]['item_id']);
						$su_data_array = array( // 10 fields
							'item_id' =>  $su_data->item_id,
							'barcode' => $su_data->item_number,
							'name' => $su_data->name,
							'category' => $su_data->category,
							'subcategory' => $su_data->subcategory,
							'brand' => $su_data->brand,
							'size' => $su_data->custom2,
							'color' => $su_data->custom3,
							'quantity' => $data[26],
							'price' => ($su_data->unit_price < 1) ? json_decode($su_data->cost_price)->retail : $su_data->unit_price
						);

						$stock_up_items[] = $su_data_array;
					}
					else if($redundancy_count > 1) // If more than 1 count for an item, then create entry in error log
					{
						$rd_item_data = $this->get_redundant_item($item_data, "get");
						$error_data_array = array(
							'item_id' => $rd_item_data[0]['item_id'],
							'item_barcode' => $rd_item_data[0]['item_number'],
							'item_name' => $rd_item_data[0]['name'],
							'redundancy_count' => $redundancy_count
						);
						$data = array(
							'error_data' => json_encode($error_data_array),
							'time' => date('Y-m-d H:i:s')
						);
						$this->db->insert('redundancy', $data);
					}
					else
					{
						if($this->Item->save($item_data)) // Creates a new item in DB
						{
							$items_taxes_data = NULL;
							//tax 1 (CGST)
							if(is_numeric($data[6]))
							{
								$items_taxes_data[] = array('name' => 'CGST', 'percent' => $data[6] );
							}

							//tax 2 (SGST)
							if(is_numeric($data[7]))
							{
								$items_taxes_data[] = array('name' => 'SGST', 'percent' => $data[7] );
							}

							//tax 3 (IGST)
							if(is_numeric($data[8]))
							{
								$items_taxes_data[] = array('name' => 'IGST', 'percent' => $data[8] );
							}

							// save tax values
							$items_taxes_data1 = (count($items_taxes_data) > 0) ? $items_taxes_data : $this->Item->tax_factory($item_data['item_id']);

							$this->Item_taxes->save($items_taxes_data1, $item_data['item_id']);

							// AUTOMATED BARCODING
							$save_item = array('item_number' => $this->Item->barcode_factory($item_data['item_id']));
							// update the item in the database in order to save the barcode field
							$this->Item->save($save_item, $item_data['item_id']);

							$new_sheet = $this->Item->get_info($item_data['item_id']);
							$new_sheet_array = array( // 10 fields
								'item_id' =>  $new_sheet->item_id,
								'barcode' => $new_sheet->item_number,
								'name' => $new_sheet->name,
								'category' => $new_sheet->category,
								'subcategory' => $new_sheet->subcategory,
								'brand' => $new_sheet->brand,
								'size' => $new_sheet->custom2,
								'color' => $new_sheet->custom3,
								'quantity' => $data[26],
								'price' => ($new_sheet->unit_price < 1) ? json_decode($new_sheet->cost_price)->retail : $new_sheet->unit_price
							);

							$new_sheet_items[] = $new_sheet_array;

							// quantities & inventory Info
							$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
							$emp_info = $this->Employee->get_info($employee_id);
							$comment ='Qty CSV Imported';

							$cols = count($data);

							// array to store information if location got a quantity
							$allowed_locations = $this->Stock_location->get_allowed_locations();
							for($col = 25; $col < $cols; $col = $col + 2)
							{
								$location_id = $data[$col];
								if(array_key_exists($location_id, $allowed_locations))
								{
									$item_quantity_data = array(
										'item_id' => $item_data['item_id'],
										'location_id' => $location_id,
										'quantity' => $data[$col + 1],
									);
									$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $location_id);

									$excel_data = array(
										'trans_items' => $item_data['item_id'],
										'trans_user' => $employee_id,
										'trans_comment' => $comment,
										'trans_location' => $data[$col],
										'trans_inventory' => $data[$col + 1]
									);

									$this->Inventory->insert($excel_data);
									unset($allowed_locations[$location_id]);
								}
							}

							/*
							* now iterate through the array and check for which location_id no entry into item_quantities was made yet
							* those get an entry with quantity as 0.
							* unfortunately a bit duplicate code from above...
							*/
							foreach($allowed_locations as $location_id => $location_name)
							{
								$item_quantity_data = array(
									'item_id' => $item_data['item_id'],
									'location_id' => $location_id,
									'quantity' => 0,
								);
								$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $data[$col]);

								$excel_data = array(
									'trans_items' => $item_data['item_id'],
									'trans_user' => $employee_id,
									'trans_comment' => $comment,
									'trans_location' => $location_id,
									'trans_inventory' => 0
								);

								$this->Inventory->insert($excel_data);
							}
						}
						else //insert or update item failure
						{
							$failCodes[] = $i;
						}
					}

					++$i;

				} // while loop ends here

				// Insert stock up items in db
				$upload_items_array = array(
					'stock_ups' => json_encode($stock_up_items),
					'new_items' => json_encode($new_sheet_items),
					'time' => date('Y-m-d H:i:s')
				);
				$this->db->insert('upload_items', $upload_items_array);

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

	public function excel_stock_up()
	{
		$this->load->view('items/form_excel_update', NULL);
	}

	public function do_excel_stock_up() #excel-stockup
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
				$error_data = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$barcode = $data[0];
					$location_id = $data[1];
					$location_quantity = $data[2];
					$pointer1 = strtoupper(trim($data[3]));
					$pointer2 = strtoupper(trim($data[4]));

					$this->db->where('item_number', $barcode);
					$count = $this->db->count_all_results('items');

					if($count == 1)
					{
						$item_id = $this->Item->get_info_by_id_or_number($barcode)->item_id;
						$new_quantity = $this->Item_quantity->get_item_quantity($item_id, $location_id)->quantity;
						$new_quantity += $location_quantity;

						$location_detail = array(
							'item_id' => $item_id,
							'location_id' => $location_id,
							'quantity' => $new_quantity
						);

						$db_pointer1 = json_decode($this->db->where('item_id', $item_id)->get('items')->row()->custom5);
						
						$db_pointer2 = json_decode($this->db->where('item_id', $item_id)->get('items')->row()->custom6);

						if(!in_array($pointer1, $db_pointer1))
						{
							$db_pointer1[] = $pointer1;
							$new_pointer1 = array('custom5' => json_encode($db_pointer1));
							$this->db->where('item_id', $item_id)->update('items', $new_pointer1);
						}

						if(!in_array($pointer2, $db_pointer2))
						{
							$db_pointer2[] = $pointer2;
							$new_pointer2 = array('custom6' => json_encode($db_pointer2));
							$this->db->where('item_id', $item_id)->update('items', $new_pointer2);
						}

				
						if($this->Item_quantity->save($location_detail, $item_id, $location_id))
						{
							$inv_data = array(
								'trans_date' => date('Y-m-d H:i:s'),
								'trans_items' => $item_id,
								'trans_user' => $this->session->userdata('person_id'),
								'trans_location' => $location_id,
								'trans_comment' => 'Qty CSV Imported',
								'trans_inventory' => $location_quantity
							);

							$this->Inventory->insert($inv_data);
						}
					}
					else
					{
						$error_data[] = $barcode;
					}

					++$i;

				} // while loop ends here

				$error_data_array = array(
					'error_data' => json_encode($error_data),
					'time' => date('Y-m-d H:i:s')
				);
				$this->db->insert('redundancy', $error_data_array);

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

	public function do_excel_stock_zero() #excel-stockup
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
				$error_data = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$barcode = $data[0];
					$location_id = $data[1];
					$location_quantity = $data[2];

					$this->db->where('item_number', $barcode);
					$count = $this->db->count_all_results('items');

					if($count == 1)
					{
						$this->db->where('item_number', $barcode);
						$item_id = $this->db->get('items')->row()->item_id;

						$current_quantity = $this->Item_quantity->get_item_quantity($item_id, $location_id)->quantity;

						$location_detail = array(
							'item_id' => $item_id,
							'location_id' => $location_id,
							'quantity' => 0
						);

						$this->Item_quantity->save($location_detail, $item_id, $location_id);
						if($this->Item_quantity->save($location_detail, $item_id, $location_id))
						{
							$inv_data = array(
								'trans_date' => date('Y-m-d H:i:s'),
								'trans_items' => $item_id,
								'trans_user' => $this->session->userdata('person_id'),
								'trans_location' => $location_id,
								'trans_comment' => 'Qty CSV Imported',
								'trans_inventory' => ($current_quantity * -1)
							);

							$this->Inventory->insert($inv_data);
						}
					}
					else
					{
						$error_data[] = $barcode;
					}

					++$i;

				} // while loop ends here

				$error_data_array = array(
					'error_data' => json_encode($error_data),
					'time' => date('Y-m-d H:i:s')
				);
				$this->db->insert('redundancy', $error_data_array);

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

	public function count_items($brand)
	{
		$count = 0;
		$array = array(
			'brand' => $brand,
			'deleted' => 0
		);
		$this->db->where($array);
		$items = $this->db->get('items')->result_array();
		foreach($items as $item)
		{
			$this->db->where('item_id', $item['item_id']);
			$this->db->where('location_id', 8);

			$count += $this->db->get('item_quantities')->row()->quantity;
		}
		echo $count;
	}

	/**
	 * Guess whether file extension is not in the table field,
	 * if it isn't, then it's an old-format (formerly pic_id) field,
	 * so we guess the right filename and update the table
	 * @param $item the item to update
	 */
	private function _update_pic_filename($item)
	{
		$filename = pathinfo($item->pic_filename, PATHINFO_FILENAME);

		// if the field is empty there's nothing to check
		if(!empty($filename))
		{
			$ext = pathinfo($item->pic_filename, PATHINFO_EXTENSION);
			if(empty($ext))
			{
				$images = glob('./uploads/item_pics/' . $item->pic_filename . '.*');
				if(sizeof($images) > 0)
				{
					$new_pic_filename = pathinfo($images[0], PATHINFO_BASENAME);
					$item_data = array('pic_filename' => $new_pic_filename);
					$this->Item->save($item_data, $item->item_id);
				}
			}
		}
	}
}
?>
