<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private $db;
	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
	}
	
	public function index()
	{
		$this->load->model('Nca_model', 'nca');
		$nca = $this->nca->regexNca(__DIR__);
		$this->nca->__updateNca($nca);
		$this->load->view('welcome_message');
	}
}
