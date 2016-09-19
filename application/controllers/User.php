<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
	public function index()
	{
		$data['title'] = '用户管理';
		$this->load->library('form_validation');
		$this->load->config('sysconfig', true);
		$data['state'] = $this->config->item('sysconfig')['user_state'];
		$data['type'] = $this->config->item('sysconfig')['user_type'];
		$index = $this->uri->segment(3);
		$where['type!='] = 1;
		if ($this->input->get('name')) $where['name'] = $this->input->get('name');
		$this->load->model('DB_model', 'db');
		$result = $this->db->search('z_user', $where, $config['per_page']=10, $index);
		$data['user'] = $result['data'];
		$this->load->library('pagination');
		$config['base_url'] = site_url('user/index');
		$config['total_rows'] = $result['total'];
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$data['total_rows'] = $config['total_rows'];
		$data['per_page'] = $config['per_page'];
		$this->load->view('user/index', $data);
	}
	
	public function create()
	{
		$data['title'] = '新增用户';
		$this->load->library('form_validation');
		if ($this->form_validation->run('create'))
		{
			$this->load->model('DB_model', 'db');
			$this->db->query("CALL p_user_create(?,?,@code,@id)", [$this->input->post('username'), md5($this->input->post('password').SESSION_KEY)]);
			$result = $this->db->query('SELECT @code as code')->row_array();
			if ($result['code'] == 200)
			{
				exit(json_encode(['ack' => true, 'msg' => '新增成功', 'url' => site_url('welcome/index')]));
			}
			else
			{
				exit(json_encode(['ack' => false, 'msg' => '用户名或密码异常']));
			}
		}
		exit(json_encode(['ack' => false, 'msg' => validation_errors()]));
	}
	
	public function set_password()
	{
		$data['title'] = '设置密码';
	}
	
	public function reset_password()
	{
		$data['title'] = '重置密码';
		$this->load->library('form_validation');
		$id = $this->input->post('id');
		if (empty($id)) exit(json_encode(['ack' => false, 'msg' => '缺少id参数']));
		if ($this->form_validation->run('password'))
		{
			$this->load->model('DB_model', 'db');
			$result = $this->db->update('z_user', ['password' => md5($this->input->post('password').SESSION_KEY)], ['id' => $id]);
			exit(json_encode(['ack' => $result, 'msg' => $result ? '重置成功' : '重置失败', 'url' => site_url('user/index')]));
		}
		exit(json_encode(['ack' => false, 'msg' => validation_errors()]));
	}
	
	public function clean_acl()
	{
		$data['title'] = '清除权限';
		$user_id = $this->uri->segment(3);
		if (empty($user_id)) show_error('非法请求, 缺少参数');
		$this->load->model('DB_model', 'db');
		// 需事务处理, 多表更新
		$result = $this->db->delete('z_user_role', ['user_id' => $user_id]);
		if ($result)
		{
			$this->db->update('z_user', ['state' => 2], ['user_id' => $user_id]);
			redirect_message('清除成功', true);
		}
		redirect_message('清除失败', false);
	}
	
	public function set_role()
	{
		$data['title'] = '设置角色';
		$id = $this->uri->segment(3);
		if (empty($id)) show_error('非法请求, 缺少参数');
		$this->load->model('DB_model', 'db');
		$user = $this->db->get_one('z_user', ['id' => $id]); 
		if (empty($user)) show_error('用户不存在');
		$this->load->config('sysconfig', true);
		$data['state'] = $this->config->item('sysconfig')['user_state'];
		$data['user'] = $user;
		$data['role'] = $data['role_ids'] = [];
		$data['role'] = $this->db->get_all('z_role');
		$role = $this->db->get_all('z_user_role', ['user_id' => $id]);
		foreach ($role as $r)
		{
			$data['role_ids'][$r['role_id']] = $r['role_id'];
		}
		$this->load->view('user/set_role', $data);
	}
	
	public function set_role_save()
	{
		$data['title'] = '设置角色(保存)';
		$id = $this->input->post('id');
		if (empty($id)) show_error('非法请求, 缺少参数');
		$role_ids = $this->input->post('role_ids') ? $this->input->post('role_ids') : []; 
		$this->load->model('user_model', 'user');
		$this->user->update_user_role($id, $role_ids);
		show_message('设置成功');
	}
}