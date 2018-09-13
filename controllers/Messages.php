<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Messages extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('messages');
		
		$this->load->library('sms_lib');
		$this->load->library('email_lib');
	}
	
	public function index()
	{
		$this->load->view('messages/sms');
	}

	public function view($person_id = -1)
	{ 
		$info = $this->Person->get_info($person_id);
		foreach(get_object_vars($info) as $property => $value)
		{
			$info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $info;

		$this->load->view('messages/form_sms', $data);
	}

	public function send()
	{	
		$phone   = $this->input->post('phone');
		$message = $this->input->post('message');

		$response = $this->sms_lib->sendSMS($phone, $message);

		if($response)
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('messages_successfully_sent') . ' ' . $phone));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('messages_unsuccessfully_sent') . ' ' . $phone));
		}
	}
	
	public function send_form($person_id = -1)
	{	
		$phone   = $this->input->post('phone');
		$message = $this->input->post('message');

		$response = $this->sms_lib->sendSMS($phone, $message);

		if($response)
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('messages_successfully_sent') . ' ' . $phone, 'person_id' => $this->xss_clean($person_id)));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('messages_unsuccessfully_sent') . ' ' . $phone, 'person_id' => -1));
		}
	}

	public function automail()
	{
		$url = "http://localhost/newpos/public/messages/send_daily_sales";

		$ch = curl_init();
		$timeout = 5;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_POSTREDIR, 3);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$data = curl_exec($ch);

		curl_close($ch);

		// echo $data;
	}

	public function message($to = 'World')
	{
		echo "Hello {$to}!".PHP_EOL;
	}

	public function send_daily_sales()
	{
		$search = '';
		$limit = 100;
		$offset = 0;
		$sort = '';
		$order = 'asc';
		$filtr = array();

		$filters = array(
			'sale_type' => 'all',
			'start_date' => date('Y-m-d'),
			'end_date' => date('Y-m-d'),
			'only_cash' => FALSE,
			'only_due' => FALSE,
			'only_check' => FALSE,
			'only_invoices' => $this->config->item('invoice_enable') && $this->input->get('only_invoices'),
			'is_valid_receipt' => $this->Sale->is_valid_receipt($search),
		);

		$reporting_locations = array(
			'IP' => 4,
			'AP' => 10,
			'BK' => 11,
			'MH' => 13
		);

		foreach($reporting_locations as $key=>$value)
		{
			$filters['location_id'] = $value;
			// check if any filter is set in the multiselect dropdown
			$filledup = array_fill_keys($filtr, TRUE);
			$filters = array_merge($filters, $filledup);

			$sales = $this->Sale->search($search, $filters, $limit, $offset, $sort, $order);
			$payments = $this->Sale->get_payments_summary($search, $filters);
			$payment_summary[$key] = $this->xss_clean(get_sales_manage_payments_summary($payments, $sales));
		}

		$data['payment_summary'] = $payment_summary;
		$to = 'abc@xyz.com';
		$subject = 'Test Mail';
		$message = $this->load->view('messages/mail/daily_sales', $data, TRUE);
		$result = $this->email_lib->sendEmail($to, $subject, $message);
		echo $result;	
	}
}
?>
