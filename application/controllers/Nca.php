<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nca extends CI_Controller
{
	public function index()
	{
		$data['title'] = 'NCA管理';
		$this->load->library('form_validation');
		$index = $this->uri->segment(3);
		$where = [];
		if ($this->input->get('controller')) $where['controller'] = $this->input->get('controller');
		if ($this->input->get('action')) $where['action'] = $this->input->get('action');
		$this->load->model('DB_model', 'db');
		$result = $this->db->search('z_nca', $where, $config['per_page']=10, $index);
		$data['nca'] = $result['data'];
		$this->load->library('pagination');
		$config['base_url'] = site_url('nca/index');
		$config['total_rows'] = $result['total'];
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$data['total_rows'] = $config['total_rows'];
		$data['per_page'] = $config['per_page'];
		$this->load->view('nca/index', $data);
	}
	
	public function refresh_nca()
	{
		$data['title'] = 'NCA刷新';
		$this->load->model('Nca_model', 'nca');
		$nca = $this->nca->regex_nca(__DIR__);
		$this->nca->_update_nca($nca);
		redirect_message('刷新成功', true);
	}
	
	public function save()
	{
		$data['title'] = 'NCA保存';
		$this->load->model('DB_model', 'db');
		foreach ($this->input->post('role_access_control') as $k => $val)
		{
			$data = [
					'role_access_control' => $this->input->post('role_access_control')[$k],
					'desc' => $this->input->post('desc')[$k],
					'show' => isset($this->input->post('show')[$k]) ? 1 : 0,
					'sort' => $this->input->post('sort')[$k],
					'param' => $this->input->post('param')[$k]
			];
			$this->db->update('z_nca', $data, ['id' => $k]);
		}
		redirect_message('保存成功', true);
	}
	
	public function refresh_acl()
	{
		$data['title'] = 'ACL刷新';
		$this->load->model('nca_model', 'nca');
		$this->nca->refresh_acl();
		redirect_message('刷新成功', true);
	}
	
	public function set_role()
	{
		$data['title'] = '分配权限-绑定角色';
		$id = $this->uri->segment(3);
		if (empty($id)) show_error('非法请求, 缺少参数');
		$this->load->model('DB_model', 'db');
		$data['nca'] = $this->db->get_one('z_nca', ['id' => $id]);
		$data['role_ids'] = [];
		$role_ids = $this->db->get_all('z_acl', ['nca_id' => $id], 'role_id');
		foreach ($role_ids as $ri)
		{
			$data['role_ids'][] = $ri['role_id'];
		}
		$data['role'] = $this->db->get_all('z_role');
		$this->load->view('nca/set_role', $data);
	}
	
	public function set_role_save()
	{
		$data['title'] = '分配权限-绑定角色(保存)';
		$id = $this->input->post('id');
		if (empty($id)) show_error('非法请求, 缺少参数');
		$role_ids = $this->input->post('role_ids') ? $this->input->post('role_ids') : [];
		$this->load->model('Nca_model', 'nca');
		$this->nca->update_acl($id, $role_ids);
		$this->nca->refresh_acl();
		show_message('绑定成功');
	}
}