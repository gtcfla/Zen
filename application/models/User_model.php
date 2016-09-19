<?php
class User_model extends CI_Model
{
	private $db;
	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
		// 设置日志保存路径
		SeasLog::setLogger('z.com');
	}

	public function update_user_role($user_ids, $role_ids)
	{
		if (is_array($role_ids))
		{
			$user_roles =  $this->db->get_where('z_user_role', ['user_id' => $user_ids])->result_array();
			$old_roles = array();
			foreach ($user_roles as $ur)
			{
				$old_roles[] = $ur['role_id'];
			}
			$roles_create = array_diff($role_ids, $old_roles);
			$roles_delete = array_diff($old_roles, $role_ids);
			foreach ($roles_create as $rc)
			{
				$this->db->insert('z_user_role', ['user_id' => $user_ids, 'role_id' => $rc]);
			}
			foreach ($roles_delete as $rd)
			{
				$this->db->delete('z_user_role', ['user_id' => $user_ids, 'role_id' => $rd]);
			}
		}
		else
		{
			$role_users = $this->db->get_where('z_user_role', ['role_id' => $role_ids])->result_array();;
			$old_users = array();
			foreach ($role_users as $ru)
			{
				$old_users[] = $ru['user_id'];
			}
			$users_create = array_diff($user_ids, $old_users);
			$users_delete = array_diff($old_users, $user_ids);
			foreach ($users_create as $uc)
			{
				$this->db->insert('z_user_role', ['user_id' => $uc, 'role_id' => $role_ids]);
			}
			foreach ($users_delete as $ud)
			{
				$this->db->delete('z_user_role', ['user_id' => $ud, 'role_id' => $role_ids]);
			}
		}
		return ['ack' => true, 'msg' => ''];
	}
}