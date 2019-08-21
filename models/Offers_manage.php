<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offers_manage extends CI_Model
{
	public function get_custom_data($where='', $id=''){
		if(empty($id)){
			$this->db->select('tag');
		}
		else{
			$this->db->select('*');	
		}
		$this->db->from('custom_fields');
		if(!empty($id)){
			$this->db->where($where,$id);
		}			
		$this->db->distinct();
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	public function get_all_data(){
		$this->db->select('*');
		$this->db->from('custom_fields');
		$this->db->distinct();
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	public function get_status($id=''){
		$this->db->select('*');
		$this->db->from('custom_fields');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$data = $query->result();
		
		return true;
	}

	public function update_status($id,$status){
			echo $id;
			echo $status;
			
		  	if($status == 'false'){
				$data = array('status'=> 0);
				echo '1';
			}
			else{
				echo '0';
				$data = array('status' => 1);
			}
			$this->db->where('id', $id);		
		   $result = $this->db->update('custom_fields',$data);
		   if($result){
		   	echo 'true';
		   }
		}

	public function add_tags($data){
		//$this->db->from('custom_fields');
		$this->db->insert('custom_fields',$data);
		return true;
	}

	public function get_cashier(){
		$this->db->select('*');
		$this->db->from('cashiers');
		$this->db->where('status','checked');
		$query = $this->db->get();
		$data = $query->result();
		return $data ;
	}
}	
?>