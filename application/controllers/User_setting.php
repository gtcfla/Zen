<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_setting extends CI_Controller
{
	
	public function index ()
	{
		$data['title'] = '设置';
	}
	
	public function refresh_nca ()
	{
		$data['title'] = 'NCA刷新';
	}
	
	public function  nca_list()
	{
		$data['title'] = 'NCA列表';
	}
	
	public function nca_save ()
	{
		$data['title'] = 'NCA保存';
	}
	
	public function refresh_acl ()
	{
		$data['title'] = 'ACL刷新';
	}
}