<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function index()
	{
		$data['title'] = 'ZEN - 登录';
		$this->load->view('login', $data);
	}
	
	public function check()
	{
		$data['title'] = 'ZEN - 登录验证';
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[2]|max_length[50]', array('required' => '账号必填', 'min_length' => '账号长度不合法'));
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]', array('required' => '密码必填', 'min_length' => '密码长度不合法'));
		if ($this->form_validation->run())
		{
			echo 'ok';
		}
		else
		{
			exit(json_encode(array('ack' => false, 'msg' => validation_errors())));
		}
	}
}