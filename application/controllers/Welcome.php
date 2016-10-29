<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function index()
	{
		$data['title'] = 'Welcome';
		$this->load->view('system', $data);
	}
	
	public function t()
	{
		dump($_SESSION);
		$this->session->set_userdata('test', 1);
		dump($_SESSION);exit;
	}
	
	public function s()
	{
		dump($this->session->userdata('test'));exit;
	}
}
