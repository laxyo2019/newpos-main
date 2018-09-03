<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pricing class
 */

class Pricing extends CI_Model
{
	public function get_active_shops()
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');

		$shop_types = array('dbf', 'shop', 'franchise');
		$this->db->where_in('login_type', $shop_types);
		$this->db->where('deleted', 0);
		return $this->db->get()->result_array();
	}

  public function get_all($employee_id)
  {
  	$this->db->from('special_prices');
		$this->db->join('items', 'items.item_id = special_prices.item_id', 'left');

		// if($stock_location_id > -1)
		// {
		// 	$this->db->join('item_quantities', 'item_quantities.item_id = items.item_id');
		// 	$this->db->where('location_id', $stock_location_id);
		// }

		$this->db->where('items.deleted', 0);

		// order by name of item
		$this->db->order_by('items.name', 'asc');

		// if($rows > 0)
		// {
		// 	$this->db->limit($rows, $limit_from);
		// }

		return $this->db->get()->result_array();
	}
	
	public function get_core_plans()
	{
		return $this->db->where('tag', 'special_pricing')
		->get('custom_fields')
		->result_array();
	}

	public function pointer_search($plan, $info)
	{
		$array = array(
			'status' => 'checked',
			'plan' => $plan,
			'locations' => $this->session->userdata('person_id')
		);
		$this->db->where($array);
		$results = $this->db->get('special_prices')->result_array();
		foreach($results as $row)
		{
			$offer = ($plan == "mixed") ? json_decode($row['pointer']) : $row['pointer'];
			if($offer == $info)
			{
				return $row;
			}
			else
			{
				return "NO_MATCH";
			}
		}
	}

	public function check_active_offers($item_id)
	{
		$response = array();
		$item_row = $this->Item->get_info($item_id);
		$item_info = array( // DO NOT CHANGE ORDER (SET ON PRIORITY RULE)
			'single' => $item_row->item_number,
			// 'sublist' => $item_row->item_number,
			'mixed' => [$item_row->category, $item_row->subcategory, $item_row->brand],
			'brand' => $item_row->brand,
			'subcategory' => $item_row->subcategory,
			'category' => $item_row->category
		);

		foreach($item_info as $key=>$value)
		{
			$result = $this->pointer_search($key, $value);
			if(!empty($result))
			{
				return $result;
			}
		}

	}

}