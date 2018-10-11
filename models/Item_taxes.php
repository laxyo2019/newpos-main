<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Item_taxes class
 */
class Item_taxes extends CI_Model
{
	public function create_array($item_id, $percent) #3105-1
	{
		return $array = array(
			array(
				'item_id' => $item_id,
				'name' => 'CGST',
				'percent' => $percent
			),
			array(
				'item_id' => $item_id,
				'name' => 'SGST',
				'percent' => $percent
			),
		);
	}

	public function create_igst_array($item_id, $percent) #3105-1
	{
		return $array = array(
			array(
				'item_id' => $item_id,
				'name' => 'IGST',
				'percent' => $percent
			)
		);
	}

	/*
	Gets tax info for a particular item
	*/
	public function get_info($item_id, $amt)
	{
		$taxtype = $this->session->userdata('taxtype');
		$category = $this->Item->get_info($item_id)->category;
		
		$main_array = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];
		$clothes_array = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING"];
		$footwear_array = ["MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];

		if(in_array($category, $main_array))
		{
			if(in_array($category, $clothes_array)) //CLOTHES
			{
				if($taxtype) //IGST
				{
					return ($amt > 1000) ? $this->create_igst_array($item_id, 12) : $this->create_igst_array($item_id, 5);
				}
				else //CSGT+SGST
				{
					return ($amt > 1000) ? $this->create_array($item_id, 6) : $this->create_array($item_id, 2.5);
				}
			}
			else if(in_array($category, $footwear_array)) //FOOTWEARS
			{
				if($taxtype) //IGST
				{
					return ($amt > 1000) ? $this->create_igst_array($item_id, 18) : $this->create_igst_array($item_id, 5);
				}
				else //CSGT+SGST
				{
					return ($amt > 1000) ? $this->create_array($item_id, 9) : $this->create_array($item_id, 2.5);
				}
			}
		}
		else
		{
			$this->db->from('items_taxes');
			$this->db->where('item_id',$item_id);
			if($taxtype)
			{
				$array = array('IGST');
			}
			else
			{
				$array = array('SGST', 'CGST');
			}
			$this->db->where_in('name', $array);

			//return an array of taxes for an item
			return $this->db->get()->result_array();
		}
	}

	public function get_specific_tax($item_id)
	{
		$response = array();
		$tax_data = $this->db->where('item_id', $item_id)->get('items_taxes')->result_array();
		foreach($tax_data as $row)
		{
			$response[$row['name']] = $row['percent'];
		}
		return $response;
	}

	public function get_sales_tax($sale_id, $item_id)
	{
		$tax_percents = array();
		$tax_amounts = array();
		$array = array(
			'sale_id' => $sale_id,
			'item_id' => $item_id
		);
		$tax_data = $this->db->where($array)->get('sales_items_taxes')->result_array();

		foreach($tax_data as $row)
		{
			$response1[$row['name']] = $row['percent'];
		}

		foreach($tax_data as $row)
		{
			$response2[$row['name']] = $row['item_tax_amount'];
		}

		return array(
			'tax_percents' => $response1,
			'tax_amounts' => $response2
		);
	}

	public function get_taxes_for_item_form($item_id)
	{
		$this->db->from('items_taxes');
		$this->db->where('item_id', $item_id);

		//return an array of taxes for an item
		return $this->db->get()->result_array();
	}

	public function get_item_invoice_tax_rate($item_id, $price, $discount)
	{
		$discounted_unit = $this->sale_lib->get_discounted_unit($price, $discount);
		$loop = $this->get_info($item_id, $discounted_unit);
		$tax_rate = 0;
		
		foreach($loop as $row)
		{
			$tax_rate += $row['percent'];
		}
		echo number_format($tax_rate);
	}
	
	/*
	Inserts or updates an item's taxes
	*/
	public function save(&$items_taxes_data, $item_id)
	{
		$success = TRUE;
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		$this->delete($item_id);
		foreach($items_taxes_data as $row)
		{
			$row['item_id'] = $item_id;
			$success &= $this->db->insert('items_taxes', $row);
		}
		$this->db->trans_complete();
		$success &= $this->db->trans_status();
		return $success;
	}
	/*
	Saves taxes for multiple items
	*/
	public function save_multiple(&$items_taxes_data, $item_ids)
	{
		$success = TRUE;
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		foreach(explode(':', $item_ids) as $item_id)
		{
			$this->delete($item_id);
			foreach($items_taxes_data as $row)
			{
				$row['item_id'] = $item_id;
				$success &= $this->db->insert('items_taxes', $row);
			}
		}
		$this->db->trans_complete();
		$success &= $this->db->trans_status();
		return $success;
	}
	/*
	Deletes taxes given an item
	*/
	public function delete($item_id)
	{
		return $this->db->delete('items_taxes', array('item_id' => $item_id));
	}
}
?>