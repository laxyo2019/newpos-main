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
		$this->load->view('special_pricing/dashboard');
	}

	public function add_item_form()
	{
		$this->load->view('special_pricing/add_item');
	}
}