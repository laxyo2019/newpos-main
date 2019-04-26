<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Control_Panel extends CI_Model
{
  //fetch only inserted data
	public function get_mci_info($id,$type)
	{
    $tblname = "master_".$type;
    $data = $this->db->get_where($tblname,array('id'=>$id))->row();
    return $data->name.", ";
  }

  //fetch all data
  public function get_mci_info2($type)
	{
    $tblname = "master_".$type;
    $data = $this->db->get($tblname);
    return $data->result();
  }
    
  public function fetch_username($obj,$tblname)
  {
    $array = json_decode($obj);
    $data = "";
    $rows = $this->db->select('username')
              ->where_in('person_id',$array)
              ->get($tblname)
              ->result();

    foreach($rows as $row)
    {
      $data .= $row->username.", ";
    }
    return $data;
  }   

  public function fetch_title($bundle)
  {
    $tblname = "master_".json_decode($bundle)->type;
    $array = json_decode($bundle)->entities;
    $data = "";
    $rows = $this->db->select('name')
              ->where_in('id',$array)
              ->get($tblname)
              ->result();

    foreach($rows as $row)
    {
      $data .= $row->name.", ";
    }
    return $data;
  }

  //used to retrive single cell e.g. title of location group or pointer group--
  public function fetch_single_column($tablename,$col,$where){
    $this->db->select($col);
    $data = $this->db->get_where($tablename,$where)->row()->$col;
    return $data;
  }

  public function delete_entire_row($tblname,$where){
    $this->db->delete($tblname,$where);
  }
}   
?>
