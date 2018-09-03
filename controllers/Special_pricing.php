<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Special_pricing extends Secure_Controller
{
  public function __construct()
	{
		parent::__construct('special_pricing');
	}

	public function index()
	{
		$data['plans'] = $this->Pricing->get_core_plans();
		$this->load->view('special_pricing/dashboard', $data);
	}

	public function test($item_id)
	{
		// echo $this->Pricing->check_active_offers($item_id);
		foreach($this->Pricing->check_active_offers($item_id) as $row)
		{
			echo $row."<br>";
		}
	}

	public function make_barcode_list()
	{
		$this->load->view('special_pricing/form_excel_barcodes');
	}

	public function do_make_barcode_list()
	{
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{
			if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
			{
				// Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();
				$barcodes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);
					$barcodes[] = $data[0]; //barcode

					++$i;

				} // while loop ends here
				$this->session->set_userdata('barcode_list', $barcodes);

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
			}
		}
	}

	public function add_basic_form($id = -1)
	{
		$data['offer_data'] = ($id != -1) ? $this->db->get_where('special_prices', array('id' => $id))->row() : '';
		$mci_data = $this->Item->get_mci_data('all');
		$categories = array('' => $this->lang->line('items_none'));
		foreach($mci_data['categories'] as $row)
		{
			$categories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['categories'] = $categories;

		$subcategories = array('' => $this->lang->line('items_none'));
			foreach($mci_data['subcategories'] as $row)
			{
				$subcategories[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
			}
		$data['subcategories'] = $subcategories;

		$brands = array('' => $this->lang->line('items_none'));
		foreach($mci_data['brands'] as $row)
		{
			$brands[$this->xss_clean($row['name'])] = $this->xss_clean($row['name']);
		}
		$data['brands'] = $brands;

		foreach($this->Pricing->get_active_shops() as $row)
		{
			$active_shops[$this->xss_clean($row['person_id'])] = $this->xss_clean($row['first_name']);
		}
		$data['active_shops'] = $active_shops;

		$data['plans'] = $this->Pricing->get_core_plans();
		$this->load->view('special_pricing/add_basic', $data);
	}

	public function add_basic_save()
	{
		$locations = $this->input->post('locations');
		$plan = $this->input->post('plan');
		$pointer = ($plan == 'mixed') ? json_encode($this->input->post('pointer')) : trim($this->input->post('pointer'));

		$array = array(
			'locations' => $locations,
			'pointer' => $pointer
		);
		$offer_count = $this->db->where($array)->count_all_results('special_prices');

		if($offer_count != 0)
		{
			echo "Offer Already Exists";
		}
		else
		{
			// $start_time = $this->input->post('start_time');
			// $end_time = $this->input->post('end_time');
			// if(strtotime(date("Y-m-d H:i:s")) <= strtotime($start_time) && strtotime($start_time) < strtotime($end_time))
			// {
				$data = array(
					'plan' => $plan,
					'locations' => $locations,
					'pointer' => $pointer,
					'price' => $this->input->post('price'),
					'discount' => $this->input->post('discount'),
					// 'start_time' => $start_time,
					// 'end_time' => $end_time
				); 
			
				$this->db->insert('special_prices', $data);
				echo "Created Successfully";
			// }
			// else
			// {
			// 	echo "Invalid Date Range";
			// }
		}
	}

	public function offer_toggle()
	{
		$status = ($this->input->post('status') == 'true') ? 'checked' : '';
		$data = array(
			'status' => $status
		);
		$this->db->where('id', $this->input->post('id'));
		echo ($this->db->update('special_prices', $data)) ? 'success' : 'failed';
	}

	public function get_offers_sublist()
	{
		$data['offers'] = $this->db->where('plan', $this->input->post('plan'))->get('special_prices')->result_array();

		$this->load->view('special_pricing/offers_sublist', $data);
	}

	
}