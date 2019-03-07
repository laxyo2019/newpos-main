<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Home extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct(NULL, NULL, 'home');
	}

	public function index()
	{
	
		if($this->session->userdata('person_id')==16||$this->session->userdata('person_id')==15){
			
			$this->db->where('deleted',0);
			$query = $this->db->get('stock_locations');	
			$data['shops']=$query->result();
			$this->db->where(array('deleted'=>0));
			$this->db->get('stock_locations');
			$this->load->view('home/admin_home',$data);
		}else{			
			$this->load->view('home/home');
		}
	}

	public function exists($location_id = -1)
	{
		$this->db->from('stock_locations');
		$this->db->where('location_id', $location_id);
		return ($this->db->get()->num_rows() >= 1);
	}

	public function item_count()
	{
		$count = 0;
	$location_row = $this->db->where('location_owner', $this->session->userdata('person_id'))
	//	$location_row = $this->db->where('location_owner',13)
			->get('stock_locations')
			->row();
			//$location_row->location_id--- 11 for mh_shop

			if(isset($location_row->location_id))
			{
				$this->db->select_sum('quantity');
				$this->db->where('location_id',$location_row->location_id);
				$query = 	$this->db->get('item_quantities');
				$count= $query->row()->quantity;
				$data['count'] = $count;
			}
			else
			{
				$data['count'] = 0;
			}
		
			echo $data['count'];
		}
		
    public function sales_count()
    {	

			$a = date('Y-m-d');
			$b = date('Y-m-d');                      ;
    	$sales = $this->db->select('*')
				->from('sales')
				->where('employee_id', $this->session->userdata('person_id'))
    	  ->where('DATE(sale_time) BETWEEN "'.rawurldecode($a).'" AND "'.rawurldecode($b).'"')
				->get()
				->result_array();
        echo count($sales); 
     }
 
	
    public function total_sales()
    {
			$a = date('Y-m-d');
			$b = date('Y-m-d');                      
    	$total_sales = $this->db->select_sum('payment_amount')
				->from('sales_payments')
				->join('sales', 'sales_payments.sale_id = sales.sale_id')
				->where('employee_id', $this->session->userdata('person_id'))
				->where('DATE(sale_time) BETWEEN "'.rawurldecode($a).'" AND "'.rawurldecode($b).'"')
				->get()->result_array();				
				$earning = $total_sales[0]['payment_amount']?$total_sales[0]['payment_amount']:0;				 
			echo $earning;
    }
	
		public function admin_count(){
			$location_id= $this->input->get('loc');
			$location_ow= $this->input->get('per');
			//Total
			$this->db->select_sum('quantity');
			$this->db->where('location_id',$location_id);
			$query = 	$this->db->get('item_quantities');
			$data['itemcount']= $query->row()->quantity?$query->row()->quantity:0; 

			//Today's sell
			$a = date('Y-m-d');
			$b = date('Y-m-d');                      ;
    	$sales = $this->db->select('*')
				->from('sales')
				->where('employee_id', $location_ow)
    	  ->where('DATE(sale_time) BETWEEN "'.rawurldecode($a).'" AND "'.rawurldecode($b).'"')
				->get()
				->result_array();
				$data['dailySales']= count($sales)?count($sales):0; 
				
				//Total sell
				$total_sales = $this->db->select_sum('payment_amount')
				->from('sales_payments')
				->join('sales', 'sales_payments.sale_id = sales.sale_id')
				->where('employee_id', $location_ow)
				->where('DATE(sale_time) BETWEEN "'.rawurldecode($a).'" AND "'.rawurldecode($b).'"')
				->get()->result_array();	
			$totSell= $total_sales[0]['payment_amount'];
			$data['totalSales']= $totSell?$totSell:0; 
			echo json_encode($data);
		}

	public function logout()
	{
		$this->Employee->logout();
	}

	/*
	Loads the change employee password form
	*/
	public function change_password($employee_id = -1)
	{
		$person_info = $this->Employee->get_info($employee_id);
		foreach(get_object_vars($person_info) as $property => $value)
		{
			$person_info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $person_info;

		$this->load->view('home/form_change_password', $data);
	}

	/*
	Change employee password
	*/
	public function save($employee_id = -1)
	{
		if($this->input->post('current_password') != '' && $employee_id != -1)
		{
			if($this->Employee->check_password($this->input->post('username'), $this->input->post('current_password')))
			{
				$employee_data = array(
					'username' => $this->input->post('username'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'hash_version' => 2
				);

				if($this->Employee->change_password($employee_data, $employee_id))
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('employees_successful_change_password'), 'id' => $employee_id));
				}
				else//failure
				{
					echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_unsuccessful_change_password'), 'id' => -1));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
			}
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
		}
	}
}
?>
