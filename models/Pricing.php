<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pricing class
 */

class Pricing extends CI_Model
{
	public function check_validity($validity)
	{
		$now = time();
		$db_time = strtotime($validity);
		if($db_time > $now)
		{
		  return TRUE;
		}

		return FALSE;
	}
	
	public function get_favorite_location()
	{
		return "10";
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
	
	public function get_special_price($item_id)
	{
		$array = array(
			'item_id' => $item_id,
			'status' => 1
		);
		$this->db->where($array);
		$count = $this->db->count_all_results('special_prices');
		if($count > 0)
		{
			$this->db->where($array);
			return $this->db->get('special_prices')->row()->price;
		}
		
		return FALSE;
	}

}