<?php
class DB_model extends CI_Model
{
	private $db;
	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
		// 设置日志保存路径
		SeasLog::setLogger('z.com');
	}
	
	public function query($sql, $where=[])
	{
		return $this->db->query($sql, $where);
	}
	
	public function insert($table, $data)
	{
		$result = $this->db->insert($table, $data);
		SeasLog::info(__METHOD__ . ' | ' . $this->db->last_query());
		if ($this->db->error()['message']) SeasLog::error($this->db->error()['message']);
		return $result;
	}
	
	public function update($table, $data, $where)
	{
		$result = $this->db->update($table, $data, $where);
		SeasLog::info(__METHOD__ . ' | ' . $this->db->last_query());
		if ($this->db->error()['message']) SeasLog::error($this->db->error()['message']);
		return $result;
	}
	
	public function delete($table, $where)
	{
		$result = $this->db->delete($table, $where);
		SeasLog::info(__METHOD__ . ' | ' . $this->db->last_query());
		if ($this->db->error()['message']) SeasLog::error($this->db->error()['message']);
		return $result;
	}
	
	public function get_one($table, $where=array(), $select='*')
	{
		return $this->db->select($select)
			->get_where($table, $where)
			->row_array();
	}
	
	public function get_all($table, $where=array(), $select='*')
	{
		return $this->db->select($select)
			->from($table)
			->where($where)
			->get()
			->result_array();
	}
	
	public function search($table, $where, $limit, $offset, $sort='id desc')
	{
		foreach ($where as $k => $w)
		{
			if (is_array($w))
			{
				$this->db->where_in($k, $w);
				unset($where[$k]);
			}
		}
		$this->db->where($where);
		$db = clone($this->db);
		$total = $this->db->count_all_results($table);
		$offset = (($offset>0 ? $offset : 1) - 1) * $limit;
		$this->db = $db;
		$this->db->order_by($sort);
		$this->db->limit($limit, $offset);
		$result = $this->db->get($table)->result_array();
		return ['total' => $total, 'data' => $result];
	}
}