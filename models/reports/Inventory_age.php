<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Report.php");

class Inventory_age extends Report
{
	public function getDataColumns()
	{
		return array(
      array('item_id' => 'Item ID'),
      array('item_number' => $this->lang->line('reports_item_number')),
      array('item_name' => $this->lang->line('reports_item_name')),
      array('quantity' => 'Quantity'),
      array('item_age' => 'Item Age (in days)')
    );
	}

	public function getData(array $inputs)
	{
    $this->db->select('items.item_id, items.name, items.item_number, item_quantities.quantity, items.reorder_level');
		$this->db->from('items');
		$this->db->join('item_quantities', 'items.item_id = item_quantities.item_id');
		$this->db->where('items.deleted', 0);
		if($inputs['location_id'] != 'all')
		{
			$this->db->where('item_quantities.location_id', $inputs['location_id']);
		}
		$this->db->order_by('items.item_id');

		return $this->db->get()->result_array();
	}

	public function getSummaryData(array $inputs)
	{
		return array();
	}
}
?>
