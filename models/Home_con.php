<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Employee class
 */

class Home_con extends CI_Model
{
	/*
	Determines if a given person_id is an employee
	*/
	public function get_time($id)
	{
		$this->db->select('*');
		$this->db->from('ospos_stock_locations');
		$this->db->where('location_owner',$id);
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	/*
	Gets total of rows
	*/
	public function get_today_date($id)
	{
		$this->db->select('date');
		$this->db->from('ospos_open_close_time');
		$this->db->where('location_owner',$id);
		$this->db->where('date',date('Y-m-d'));
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	public function get_login_details($id){
		$this->db->select('*');
		$this->db->from('ospos_open_close_time');
		$this->db->where('date',date('Y-m-d'));
		$this->db->where('location_owner',$id);
		$this->db->limit(20);
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	public function get_all_login($id){
		$this->db->select('*');
		$this->db->from('ospos_open_close_time');
		$this->db->where('location_owner',$id);
		$query = $this->db->get();
		$data = $query->result();
		return $data;	
	}
}
?>