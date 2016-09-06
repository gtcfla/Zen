<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
	
	public function index()
	{
		$data['title'] = '设置';
	}
	
	public function refreshNCA()
	{
		$data['title'] = 'NCA刷新';
	}
}