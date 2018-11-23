<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pricing class
 */

class Pricing extends CI_Model
{
	public function get_active_shops($shop_types)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');

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
		return $this->db->where('tag', 'special_pricing')->get('custom_fields')->result_array();
	}

	public function get_core_voucher_types()
	{
		return $this->db->where('tag', 'special_voucher')->get('custom_fields')->result_array();
	}

	public function pointer_search($plan, $info)
	{
		$now = date('Y-m-d H:i:s');
		$results = $this->db->where(
			array(
				'status' => 'checked',
				'plan' => $plan,
				'locations' => $this->session->userdata('person_id'),
				'start_time <=' => $now,
				'end_time >=' => $now
			)
		)->get('special_prices')->result_array();

		foreach($results as $row)
		{
			$offer = ($plan == 'mixed' || $plan == 'mixed2') ? json_decode($row['pointer']) : $row['pointer'];
			if($offer == $info)
			{
				return $row;
			}
		}
	}

	public function check_active_offers($item_id)
	{
		$response = array();
		$item_row = $this->Item->get_info($item_id);
		$item_info = array( // DO NOT CHANGE ORDER (SET AS PRIORITY RULE)
			'single' => $item_row->item_number,
			// 'sublist' => $item_row->item_number,
			'mixed' => [$item_row->category, $item_row->subcategory, $item_row->brand],
			'mixed2' => [$item_row->category, $item_row->brand],
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

	public function get_voucher_detail($vc_type)
	{
		$array = array(
			'tag' => 'special_pricing',
			'int_value' => $vc_type
		);
		return $this->db->where($array)->get('custom_fields')->row()->title;
	}

}