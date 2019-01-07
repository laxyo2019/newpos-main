<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

	class Warehouse extends Secure_Controller
	{
	    public function __construct()
		{
			parent::__construct('warehouse');
	    }
	    public function index()
	    {
	    	//$data['warehouse'] = 
	    	$this->load->view('warehouse/dashboard');
	    }
	    public function save_pointer()
	    {
	    	$id = $this->input->post('id');
	    	$pointer = $this->input->post('pointer');
	    
	    $insert_data = array('pointer'=>$pointer);
	    $update_data = array('pointer'=>$pointer);
	    if(!empty($id))
	    {
	    	$this->db->where('id',$id)->update('warehouse',$update_data);
	    	echo json_encode($update_data);
	    }
	    else
	    {
	    	$this->db->insert('warehouse',$insert_data);
	    	echo json_encode($insert_data);
	    }
	    }

	    public function edit_pointer($id)
	    {
		$data['pointer_data'] = $this->db->where('id', $id)->get('warehouse')->row();
		$this->load->view('warehouse/edit_pointer', $data);
	    }

	    /*public function active_pointer_window()
	    {
	    	$this->load->view('warehouse/active_pointer_window');
	    }*/

	    public function get_warehouse_item($id)
	    {
	    	$data['warehouse_list'] =$this->db->where(array('pointer_id'=>$id))->get('warehouse_items')->result_array();
	    	$this->load->view('warehouse/warehouse_item',$data);
	    }

	    public function delete_pointer()
	    {
	       $id = $this->input->post('id');
		   $this->db->delete('warehouse', array('id' => $id));
		   echo "Deleted Successfully";
	    }

	     public function delete_warehouse_item()
	     {
	     	$id = $this->input->post('id');
		   $this->db->delete('warehouse_items', array('id' => $id));
		   echo "Deleted Successfully";
	     }

	     public function excel_import()
	     {
	     	$this->load->view('warehouse/warehouse_excel_import',NULL);
	     }

	     public function do_excel_warehouse()
	     {
	     	
	     }

	     public function add_warehouse_item()
	     {
	     	$this->load->view('warehouse/add_warehouse_item');
	     }

	    public function save_warehouse_item()
	    {
	       $id = $this->input->post('id');
	       $barcode = $this->input->post('barcode');
	       $quantity = $this->input->post('quantity');
	       $insert_data = array('barcode'=>$barcode, 'quantity'=>$quantity);
	       $update_data = array('barcode'=>$barcode, 'quantity'=>$quantity);
	    if(!empty($id))
	    {
	    	$this->db->where('id',$id)->update('warehouse_items',$update_data);
	    	echo json_encode($update_data);
	    }
	    else
	    {
	    	$this->db->insert('warehouse_items',$insert_data);
	    	echo json_encode($insert_data);
	    //}	
	    }
       }
        public function edit_warehouse_item($id)
	    {
		$data['warehouse_item_data'] = $this->db->where('id', $id)->get('warehouse_items')->row();
		$this->load->view('warehouse/edit_warehouse_item',$data);
	    }
	     
}
     
	



        /*public function index()
        {
		 $data['warehouse'] = $this->Warehouse_model->get_all_pointers();
		 $this->load->view('warehouse/dashboard',$data);
		
		}
		public function add_pointer()
		{
         $this->load->view('warehouse/addpointer');
		}
		public function pointer_save()
		{
			$id = $this->input->post('id');
			$pointer = $this->input->post('pointer');
			$insert_data = array(
			'pointer' => $pointer);

			$update_data = array(
				'pointer' => $pointer
			);
			if(!empty ($id))
			{
				$this->db->where('id',$id)->update('warehouse',$update_data);
				echo json_encode($update_data);
			}
			else
			{
				$this->db->insert('warehouse', $insert_data);
				echo json_encode($insert_data);
			}
			
			
			
		}*/
