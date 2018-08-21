<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Manager extends Secure_Controller
{
  public function __construct()
	{
		parent::__construct('manager');
  }

  public function index()
  {
    $data['stock_locations'] = $this->Stock_location->get_allowed_locations();
    $data['mci_data'] = $this->Item->get_mci_data('all');
    $this->load->view('manager/dashboard', $data);
  }

  public function get_count()
  {
    $count = 0;
    $locations = $this->input->post('locations');

    $filter = $this->input->post('filter');
    foreach($filter as $key=>$value)
    {
      if(!empty($value)){
        $array[$key] = $value;
      }
    }

    $this->db->where('deleted', 0);
    $this->db->where($array);
    $filtered_items = $this->db->get('items')->result_array();

    foreach($locations as $location)
    {
      foreach($filtered_items as $row)
      {
        $this->db->where('location_id', $location);
        $this->db->where('item_id', $row['item_id']);
        $count += $this->db->get('item_quantities')->row()->quantity;
      }
    }

    echo $count;
  }

  public function count_all_items()
  {
    $count = 0;
    $locations = $this->input->post('locations');
    $this->db->where('deleted', 0);
    $items = $this->db->get('items')->result_array();


    foreach($locations as $location)
    {
      foreach($items as $row)
      {
        $this->db->where('location_id', $location);
        $this->db->where('item_id', $row['item_id']);
        $count += $this->db->get('item_quantities')->row()->quantity;
      }
    }
    
    echo $count;
  }

  public function mci_livesearch()
  {
    $keyword = $this->input->post('keyword');
    $table_name = 'master_'.$this->input->post('type');
    $this->db->like('name', $keyword);
    $results = $this->db->get($table_name)->result_array();
    foreach($results as $row)
    {
     echo '<span style="background-color:gray; color:#fff" class="form-control input-sm liveresults">'.$row['name'].'</span>';
    }
  }

  public function list_all_items()
  {
    $data['locations'] = $this->input->post('locations');
    $data['items'] = $this->Item->get_all()->result_array();
    $this->load->view('manager/items_sublist', $data);
  }

  public function list_filtered_items()
  {
    $data['locations'] = $this->input->post('locations');

    $filter = $this->input->post('filter');
    foreach($filter as $key=>$value)
    {
      if(!empty($value)){
        $array[$key] = $value;
      }
    }

    $this->db->where('deleted', 0);
    $this->db->where($array);
    $data['items'] = $this->db->get('items')->result_array();
    $this->load->view('manager/items_sublist', $data);
  }

  public function fetch_stockup_items()
  {
    $items = $this->db->order_by('id',"desc")
    ->limit(1)
    ->get('stock_up_items')
    ->row('items');
    
    $data['items'] = json_decode($items);

    $this->load->view('manager/stockup_sublist', $data);
  }

  public function get_mci_list()
  {
    $table = 'master_'.$this->input->post('type');
    $data['mci_data'] = $this->db->get($table)->result_array();
    $this->load->view('manager/mci_sublist', $data);
  }

  public function get_mci_sublist()
  {
    $parent_id = $this->input->post('parent_id');
    $data['mci_data'] = $this->db->where('parent_id', $parent_id)->get('master_subcategories')->result_array();
    $this->load->view('manager/mci_sublist', $data);
  }

  public function mci_save()  // create new cat,subcat,brand,size,color
	{
    $type = $this->input->post('type');
		$name = strtoupper(trim($this->input->post('name')));

		if($type == "subcategories")
		{
			$id = trim($this->input->post('id'));
			$parent_id = $this->input->post('parent_id');
			$data = array(
				'id' => $id,
				'name' => $name,
				'parent_id' => $parent_id
			);
		}
		else if($type == "categories")
		{
			$id = trim($this->input->post('id'));
			$data = array(
				'id' => $id,
				'name' => $name
			);
		}
		else
		{
			$data = array(
				'name' => $name,
			);
		}

    $tablename = 'master_'.$type;
    $count = $this->db->where('name', $name)->count_all_results($tablename);
    if($count == 0)
    {
      $this->db->insert($tablename, $data);
      echo "Successfully Created";
    }
    else
    {
			echo "Duplicate Entry Not Saved!";
		}
	}

	public function mci_update()  // update existing cat,subcat,brand,size,color
	{
		$name = strtoupper(trim($this->input->post('name')));
		$type = $this->input->post('type');
		$id = $this->input->post('id');

		if($type == "subcategories")
		{
			$parent_id = $this->input->post('parent_id');
			$data = array(
				'name' => $name,
				'parent_id' => $parent_id
			);
		}
		else
		{
			$data = array(
				'name' => $name,
			);
		}
		$this->db->where('id', $id);
		$tablename = 'master_'.$type;
		$this->db->update($tablename, $data);
		echo 'Successfully Updated';
	}
  
}