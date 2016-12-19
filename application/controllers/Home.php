<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('client_model');
        $this->load->model('account_model');
		$this->load->helper('url');
	}

	
	public function index()
	{
		$this->load->view('Home/header');
		//$this->load->view('Home/main');
		$this->load->view('Home/footer');
	}

	public function getAccountTypes()
	{
		echo json_encode($this->account_model->getTypes());
	}

	
}
