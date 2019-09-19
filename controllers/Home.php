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
		$curr_month = date('m');
		$curr_year = date('Y');
		$a =  $curr_year .'-' . $curr_month . '-' . '01';
		$b = date("Y-m-t", strtotime($a));
		$query =$this->db->query('SELECT ospos_stock_locations.location_name,SUM(DISTINCT ospos_sales_payments.payment_amount) AS Total_earning,SUM(ospos_sales_items.quantity_purchased) AS total_sale
			FROM `ospos_sales`
			INNER JOIN ospos_sales_payments ON ospos_sales_payments.sale_id =ospos_sales.sale_id 
			INNER JOIN ospos_stock_locations ON ospos_sales.employee_id=ospos_stock_locations.location_owner 
			INNER join ospos_sales_items ON ospos_sales_items.sale_id=ospos_sales.sale_id 
			WHERE DATE(ospos_sales.sale_time) BETWEEN "'.rawurldecode($a).'" AND "'.rawurldecode($b).'"
			GROUP BY ospos_stock_locations.location_name
			ORDER BY SUM(DISTINCT ospos_sales_payments.payment_amount) DESC');

		$data['rank']=$query->result();
		
			
		$this->session->userdata('username');
		$this->db->where('deleted',0);
		$query = $this->db->get('stock_locations');	
		$data['shops']=$query->result();
		$this->db->where(array('deleted'=>0));
		$this->db->get('stock_locations');

		$data['time']  = $this->Home_con->get_time($this->session->userdata('person_id'));
		$data['model'] = $this->Home_con->get_today_date($this->session->userdata('person_id'));
		$data['log_dtl']  = $this->Home_con->get_login_details($this->session->userdata('person_id'));
		$this->load->view('home/admin_home',$data);

		
		// if($this->session->userdata('person_id')==16||$this->session->userdata('person_id')==15){
		// 	$this->db->where('deleted',0);
		// 	$query = $this->db->get('stock_locations');	
		// 	$data['shops']=$query->result();
		// 	$this->db->where(array('deleted'=>0));
		// 	$this->db->get('stock_locations');
		// 	$this->load->view('home/admin_home',$data);
		// }
		// else{			
		// 	$this->load->view('home/home');
		// }
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
			$this->db->join('items','items.item_id = item_quantities.item_id');
			$this->db->where('location_id',$location_id);
			$this->db->where('deleted',0);
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

			$time      = $this->Home_con->get_login_details($location_ow);
			$data['logintime'] = $time[0]->logintime;
			$data['logouttime'] = $time[0]->logouttime;
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

	public function reason_save($status=''){
	
		$reason = $this->input->post('reason')?$this->input->post('reason'):'';
		$this->session->userdata('person_id');
		$data['logintime']      = date('H:i:s');
		$data['location_owner']	= $this->session->userdata('person_id');
		$data['reason']         = $reason;
		$data['date']           = date('Y-m-d');
		$data['ip']             = $_SERVER['REMOTE_ADDR'];

		$this->db->insert('ospos_open_close_time',$data);
		
		echo 'true';
	}

	public function get_all_login(){
		$id  = $this->input->post('id');
		$data['login'] = $this->Home_con->get_all_login($id);	
		return $this->load->view('home/login_details',$data);
	}	

	public function shop_close($status){
		if(!empty($status)){
			$data1['logouttime'] = date('H:i:s');
			$this->db->where('location_owner',$this->session->userdata('person_id'));
			$this->db->where('date',date('Y-m-d'));
    		$this->db->update('ospos_open_close_time', $data1);
    	}
    	redirect('home');
	}
}
?>
