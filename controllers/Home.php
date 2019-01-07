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
		$this->load->view('home/home');
	}
	public function exists($location_id = -1)
	{
		$this->db->from('stock_locations');
		$this->db->where('location_id', $location_id);

		return ($this->db->get()->num_rows() >= 1);
	}
	public function test()
	{
	   $person_id = $this->session->userdata('person_id');
	   echo $person_id;
             /*$this->db->select('*');
             $this->db->from('stock_locations');
             $this->db->where('location_owner',$person_id);*/
            return $query = $this->db->get('stock_locations');
	//$query = "select * from stock_locations where location_owner = $person_id";
	//SELECT * FROM `ospos_stock_locations` WHERE location_owner = 7
	   echo $query;
	}
	

	 public function item_count()
    {
    /*	$count = 0;
    $locations = $this->input->post('locations');
    $this->db->where('deleted', 0);
    $items = $this->db->get('items')->result_array();
    count($items);
    echo "<pre>";
    print_r( $items);*/

    /*foreach($locations as $location)
    {
      foreach($items as $row)
      {
        $this->db->where('location_id', $location);
        $this->db->where('item_id', $row['item_id']);
        $count += $this->db->get('item_quantities')->row()->quantity;
      }
    }
    */
    //echo $count;
    	$this->db->select(
        'items.name AS name,
        item_quantities.location_id AS location_id,
        item_quantities.quantity AS quantity');
    	$this->db->from('items');
    	$this->db->join('item_quantities','items.item_id=item_quantities.item_id');
    	$this->db->limit('100','1');
    	$query = $this->db->get();
    	$item = $query->/*num_rows*/result();
    	echo count($item);
    }

	public function daily_sales()
	{
		$this->db->select('sum(quantity_purchased*item_unit_price) as stockvalue');
		$this->db->from('sales_items');
		$query = $this->db->get();
		//$num = $query->num_rows();
        $dailysales = $query->row()->stockvalue;
        $a = round($dailysales,5);
        echo "Rs $a";
        //$this->load->view('home/home',$data);
    }

    public function customer_count()
    {
    	$this->db->select('*');
    	$this->db->from('customers');
    	$query = $this->db->get();
    	$customers = $query->result();
    	echo count($customers);
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
