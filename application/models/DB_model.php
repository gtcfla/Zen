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
	
	public function t()
	{
		$this->db->query('INSERT INTO sequence VALUES(0);');
		return $this->db->insert_id();
// 		$this->db->query('CALL t_sequence(@code);');
// 		$this->db->trans_start();
// 		$sn = $this->db->query('select nextval(\'o\') as sn;')->row_array();
// // 		$this->db->query('INSERT INTO sequence VALUES(0);');
// 		$order_sn = 'O'.date('ymd').sprintf('%06d', $sn['sn']);
// 		$this->db->query('INSERT INTO t_orders(`no`) VALUES(\''.$order_sn.'\')');
// 		$this->db->trans_complete();
	}
	
	public function call_function($name)
	{
		echo 123;
		$this->db->call_function('nextval', 'o');
		echo $this->db->last_query();
		exit;
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