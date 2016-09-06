<?php
class Nca_model extends CI_Model
{
	private $db;
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
	}
	
	/**
	 * 更新单个NCA
	 * @param int $nca_id
	 * @param array $data
	 */
	public function updateNcaById($nca_id, $data)
	{
		$check_in_db = self::getOne(array('id' => $nca_id));
		if (empty($check_in_db)) return array('ack' => false, 'msg' => 'nca id error');
		if (empty($check_in_db['action']) && $check_in_db['controller'])
		{
			//检测当前C的action是否为空，是则删除当前C
			$check_child  = self::getOne(array('controller' => $check_in_db['controller'], 'action!=' => ''));
			if (!$check_child)
			{
				$this->db->delete('nca', array('id' => $nca_id));
				return array('ack' => true, 'msg' => '');
			}
		}
		$this->db->update('nca', $data, array('id' => $nca_id));
		return array('ack' => true, 'msg' => '');
	}
	
	/**
	 * 更新所有NCA
	 * @param array $nca
	 * @return return array()
	 * @author zen <gtcfla@gmail.com> 2016年9月6日
	 */
	public function updateNca($nca)
	{
		$nca_old = $this->db->query('SELECT * FROM nca WHERE action!=\'\'')->result_array();
		$nca_in_db = array();
		foreach ($nca_old as $no)
		{
			$nca_in_db[$no['controller'] . '_' . $no['action']] = $no;
		}
		$create_nca = array_diff_key($nca_in_file, $nca_in_db);
		$delete_nca = array_diff_key($nca_in_db, $nca_in_file);
		$update_nca = array_diff_key($nca_in_file, $create_nca);
		$ignore_role_controller = array('api', 'login');
		foreach ($create_nca as $cn)
		{
			$controller_in_db = $this->db->get_where('nca', array('controller' => $cn['controller'], 'action' => ''))->row_array();
			if (!$controller_in_db)
			{
				$controller = array(
					'role_access_control' => in_array($cn['controller'], $ignore_role_controller) ? 'ACL_EVERYONE' : 'ACL_NULL',
					'controller' => $cn['controller'],
					'action' => '',
					'name' => $cn['controller'],
					'descript' => ''
				);
				$this->db->insert('nca', $controller);
				unset($controller,$controller_in_db);
			}
			$action = array(
				'role_access_control' => in_array($cn['controller'], $ignore_role_controller) ? 'ACL_EVERYONE' : 'ACL_NULL',
				'controller' => $cn['controller'],
				'action' => $cn['action'],
				'name' => $cn['descript'],
				'descript' => $cn['descript']
			);
			$this->db->insert('nca', $action);
			unset($action);
		}
		foreach ($delete_nca as $dn)
		{
			$this->db->delete('nca', array('id' => $dn['id']));
		}
		foreach ($update_nca as $un)
		{
			$update = $this->db->get_where('nca', array('controller' => $un['controller'], 'action' => $un['action'], 'name!=' => $un['descript']))->row_array();
			if (!empty($update['id'])) $this->db->update('nca', array('name' => $un['descript'], 'descript' => $un['descript']), array('id' => $update['id']));
		}
		return array('ack' => true, 'msg' => '');
	}
}