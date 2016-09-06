<?php
class DB_model extends CI_Model
{
	private $_db;
	
	public function __construct()
	{
		parent::__construct();
		$this->_db = $this->load->database('default', true);
	}
	
	public function getOne($table_name, $where=array(), $select='*')
	{
		return $this->_db->select($select)
			->get_where($table_name, $where)
			->row_array();
	}
	
	public function getAll($table_name, $where=array(), $select='*')
	{
		return $this->_db->select($select)
			->from($table_name)
			->where($where)
			->get()
			->result_array();
	}
}