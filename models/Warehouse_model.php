<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Warehouse_model class
 */
  class Warehouse_model extends CI_model
  {
      var $table = 'warehouse';
    public function __construct()
	{
		parent::__construct();
  }
    public function get_all_pointers()
    {
      return $this->db->get('warehouse')->result_array();
    }
   // public function addpointer()
    //{
      //return $this->db->insert('warehouse',$data);
      //return $this->db->insert_id();
    //}
    
  }