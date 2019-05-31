<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function index()
	{
		if($this->Employee->is_logged_in())
		{
			redirect('home');
		}
		else
		{
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			$this->form_validation->set_rules('username', 'lang:login_username', 'required|callback_login_check');

			if($this->config->item('gcaptcha_enable'))
			{
				$this->form_validation->set_rules('g-recaptcha-response', 'lang:login_gcaptcha', 'required|callback_gcaptcha_check');
			}

			if($this->form_validation->run() == FALSE)
			{
				$this->load->view('login');
			}
			else
			{
				redirect('home');
			}
		}
	}

	public function login_check($username)
	{
		$password = $this->input->post('password');
		
		if(!$this->_installation_check())
		{
			$this->form_validation->set_message('login_check', $this->lang->line('login_invalid_installation'));

			return FALSE;
		}

		if(!$this->Employee->login($username, $password))
		{
			$this->form_validation->set_message('login_check', $this->lang->line('login_invalid_username_and_password'));

			return FALSE;
		}

		// trigger any required upgrade before starting the application
		$this->load->library('migration');
		$this->migration->latest();

		return TRUE;
	}

	public function gcaptcha_check($recaptchaResponse)
	{
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $this->config->item('gcaptcha_secret_key') . '&response=' . $recaptchaResponse . '&remoteip=' . $this->input->ip_address();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);

		$status = json_decode($result, TRUE);

		if(empty($status['success']))
		{
			$this->form_validation->set_message('gcaptcha_check', $this->lang->line('login_invalid_gcaptcha'));

			return FALSE;
		}

		return TRUE;
	}

	private function _installation_check()
	{
		// get PHP extensions and check that the required ones are installed
		$extensions = implode(', ', get_loaded_extensions());
		// echo json_encode($extensions);
		$keys = array('bcmath', 'intl', 'gd', 'openssl', 'mbstring', 'curl');
		$pattern = '/';
		foreach($keys as $key)
		{
			$pattern .= '(?=.*\b' . preg_quote($key, '/') . '\b)';
		}
		$pattern .= '/i';
		$result = preg_match($pattern, $extensions);

		if(!$result)
		{
			error_log('Check your php.ini');
			error_log('PHP installed extensions: ' . $extensions);
			error_log('PHP required extensions: ' . implode(', ', $keys));
		}

		return $result;
	}
	public function reports()
	{
    $curr_month = date('M');
  
		$name = 'DBF_'.$curr_month.'_Report_Data'.'.csv';
    $data = file_get_contents('../reports/monthly_report/' . $name);
   force_download($name, $data);
    
	}

		/*==========Start delete csv file and save in folder=====================*/

		public function deleted_reports()
		{
			$curr_month = date('M');
	
			$name = 'DBF_'.$curr_month.'_Deleted_Bill_Report'.'.csv';
			$data = file_get_contents('../reports/bill_deleted/' . $name);
			force_download($name, $data);
	
		}
		/*==========End delete csv file and save in folder=====================*/


public function send_Email_report(){

	$email = $this->input->get_post('email');
	$category =$this->input->get_post('category');
  $curr_month_name = date('M');
  
  $from ='babaentertainment1@gmail.com';
	if ($category=="monthly_sales") {
		$subject = "DBF Monthly Sales Report";
		$message = '<b>DBF monthly report format of '. $curr_month_name . "month" . 'the download link is here </b> </br> <a href="http://localhost/newpos-lives/public/login/reports">Click Me</a>';
	}else{
		$subject = "DBF Monthly Deleted Report";
		$message = '<b>DBF monthly deleted report format of '. $curr_month_name . "month" . 'the download link is here </b> </br> <a href="http://localhost/newpos-lives/public/login/deleted_reports">Click Me</a>';
	}
      // Make the attachment
    $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'in-v3.mailjet.com',
        'smtp_port' => 25,
        'smtp_user' => '58e4acb8f3f8c17af91c68a3ea3c44d1',
        'smtp_pass' => '8ca222fc94782b04643db9510fdff46e',
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
      );
     
      $this->load->library('email');
      $this->email->initialize($config);
      $this->email->set_newline("\r\n");
      $this->email->from($from);
      $this->email->to($email);
      $this->email->subject($subject);
      $this->email->message($message);
      
    if($this->email->send())
      {?>
          <script>alert('Email Send');</script> <?php
        
      }
      else
      {
        show_error($this->email->print_debugger());
      }

}


}
?>
