<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller
{
	public function index()
	{
		$data['title'] = '角色管理';
		$data['role'] = [];
		$this->load->model('DB_model', 'db');
		$data['role'] = $this->db->get_all('z_role');
		$this->load->view('role/index', $data);
	}
	
	public function create()
	{
		$data['title'] = '新增角色';
		$desc = $this->input->post('desc');
		if (empty($desc)) exit(json_encode(['ack' => false, 'msg' => '角色名不能为空']));
		$this->load->model('DB_model', 'db');
		$result = $this->db->insert('z_role', ['desc' => $desc]);
		exit(json_encode(['ack' => $result, 'msg' => $result ? '成功' : '失败', 'url' => site_url('role/index')]));
	}
	
	public function edit()
	{
		$data['title'] = '修改角色';
		$id = $this->uri->segment(3);
		if (empty($id)) show_error('非法请求, 缺少参数');
		$data['user'] = $data['role'] = $data['user_ids'] = [];
		$this->load->model('DB_model', 'db');
		$data['user'] = $this->db->get_all('z_user', ['state' => 1, 'type' => 2]); 
		$data['role'] = $this->db->get_one('z_role', ['id' => $id]);
		$user_role = $this->db->get_all('z_user_role', ['role_id' => $id]);
		foreach ($user_role as $r)
		{
			$data['user_ids'][$r['user_id']] = $r['user_id'];
		}
		$this->load->view('role/edit', $data);
	}
	
	public function edit_save()
	{
		$data['title'] = '修改角色(保存)';
		$id = $this->input->post('id');
		$desc = $this->input->post('desc');
		$user_ids = $this->input->post('user_ids') ? $this->input->post('user_ids') : [];
		if (empty($id) && empty($desc)) show_error('非法请求, 缺少请求参数');
		$this->load->model('DB_model', 'db');
		$this->db->update('z_role', ['desc' => $desc], ['id' => $id]);
		$this->load->model('user_model', 'user');
		$this->user->update_user_role($user_ids, $id);
		show_message('成功保存');
	}
	
	public function clean_acl()
	{
		$data['title'] = '清空权限';
		$role_id = $this->uri->segment(3);
		if (empty($role_id)) show_error('非法请求, 缺少参数');
		$this->load->model('DB_model', 'db');
		$result = $this->db->delete('z_acl', ['role_id' => $role_id]);
		if ($result) redirect_message('清空成功', true);
		redirect_message('清空失败', false);
	}
	
	public function set_nca()
	{
		$data['title'] = '分配权限';
		$role_id = $this->uri->segment(3);
		$this->load->model('DB_model', 'db');
		$nca = $this->db->get_all('z_nca');
		$data['nca'] = $data['nca_ids'] = [];
		foreach ($nca as $n)
		{
			$data['nca'][$n['controller']][] = $n;
		}
		$data['role'] = $this->db->get_all('z_role');
		$nca_ids = $this->db->get_all('z_acl', ['role_id' => $role_id], 'nca_id');
		foreach ($nca_ids as $ni)
		{
			$data['nca_ids'][] = $ni['nca_id'];
		}
		$this->config->load('sysconfig', true);
		$data['config'] = $this->config->item('sysconfig')['role_access_control'];
		$this->load->view('role/set_nca', $data);
	}
	
	public function set_nca_save()
	{
		$data['title'] = '分配权限(保存)';
		if ($this->input->post('role_id'))
		{
			$nca_id = $this->input->post('nca_id') ? $this->input->post('nca_id') : [];
			$this->load->model('Nca_model', 'nca');
			$this->nca->update_acl($nca_id, $this->input->post('role_id'));
			show_message('分配成功');
		}
		show_error('非法请求, 缺少参数');
	}
}