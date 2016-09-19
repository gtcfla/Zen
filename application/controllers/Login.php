<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function index()
	{
		$data['title'] = 'ZEN - 登录';
		if (isset($_SESSION[SESSION_KEY])) redirect('welcome/index');
		$this->load->view('login', $data);
	}
	
	public function check()
	{
		$data['title'] = 'ZEN - 登录验证';
		$this->load->library('form_validation');
		if ($this->form_validation->run('login'))
		{
			$this->load->model('DB_model', 'db');
			$result = $this->db->get_one('z_user', ['name' => $this->input->post('username'), 'password' => md5($this->input->post('password').SESSION_KEY)]);
			if ($result)
			{
				$this->session->set_tempdata(SESSION_KEY, ['uid' => $result['id'], 'name' => $result['name']], 3600*24);
				exit(json_encode(['ack' => true, 'msg' => '登录验证成功', 'url' => site_url('welcome/index')]));
			}
			exit(json_encode(['ack' => false, 'msg' => '用户名或密码有误']));
		}
		exit(json_encode(['ack' => false, 'msg' => validation_errors()]));
	}
	
	public function logout()
	{
		$data['title'] = '注销';
		$this->session->sess_destroy();
		redirect('login/index');
	}
}