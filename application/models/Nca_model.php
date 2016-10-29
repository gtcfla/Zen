<?php
class Nca_model extends CI_Model
{
	private $db;
	// NCA正则匹配表达式
	private $regex = ['/class\s(.+?)\sextends/i', '/public function\s(.+?)\(.+?\{(.+?)\}/', '/data\[\'title\'\]\s*=\s*\'(.+?)\'/'];
	
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
// 		SeasLog::setLogger('z.com'); // 设置日志保存路径
	}
	
	/**
	 * 正则匹配NCA
	 * @param 匹配目录 $dir
	 * @return return array
	 * @author zen <gtcfla@gmail.com> 2016年9月8日
	 */
	public function regex_nca($dir)
	{
		$this->load->helper('directory');
		$files_map = directory_map($dir);
		$nca = [];
		foreach ($files_map as $fm)
		{
			$file_content = php_strip_whitespace($dir.'/'.$fm);
			preg_match_all($this->regex[0], $file_content, $class);
			
			if(isset($class[1][0]))
			{
				$controller = strtolower($class[1][0]);
				$namespace = explode('_', $controller);
				$controller = $namespace[0];
				$namespace = empty($namespace[1]) ? '' : $namespace[1];
				preg_match_all($this->regex[1], $file_content, $function);
				if(isset($function[1]))
				{
					foreach ($function[1] as $key => $action)
					{
						$action = strtolower($action);
						preg_match_all($this->regex[2], $function[2][$key], $title);
						$desc = empty($title[1]) ? '' : $title[1][0];
						$nca[] = [
							'namespace' => trim($namespace), 
							'controller' => trim($controller),
							'action' => trim($action),
							'desc' => trim($desc)
						];
					}
				}
			}
		}
		return $nca;
	}
	
	/**
	 * 更新所有NCA
	 * @param array $nca
	 * @author zen <gtcfla@gmail.com> 2016年9月6日
	 */
	public function _update_nca($nca)
	{
		foreach ($nca as $n)
		{
			$this->db->insert('z_nca', $n);
			if (!empty($this->db->error()['message'])) SeasLog::error($this->db->last_query() . ' | ' . $this->db->error()['message']);
		}
	}
	
	/**
	 * 刷新ACL权限
	 * @return number
	 * @return true/false
	 * @author zen <gtcfla@gmail.com> 2016年9月15日
	 */
	public function refresh_acl()
	{
		$nca = $this->db->get_where('z_nca')->result_array();
		$this->config->load('sysconfig', true);
		$config = $this->config->item('sysconfig')['role_access_control'];
		foreach ($nca as $n)
		{
			if ($n['role_access_control'])
			{
				$acl[$n['controller']]['actions'][$n['action']]['allow'] = $config[$n['role_access_control']];
			}
			else
			{
				$mapping = $this->db->select('z_user_role.user_id')->join('z_acl', 'z_acl.role_id=z_user_role.role_id')->get_where('z_user_role', ['z_acl.nca_id =' => $n['id']])->result_array();
				$roles =[];
				foreach ($mapping as $m)
				{
					$roles[$m['user_id']] = $m['user_id'];
				}
				$roles = implode(',', $roles);
				$acl[$n['controller']]['actions']['all_actions']['allow'] = '1';
				$acl[$n['controller']]['actions'][$n['action']]['allow'] = $roles ?  '1,'.$roles : '1';
			}
		}
		if ($acl)
		{
			$acl_filename = APPPATH.'config/acl.php';
			return file_put_contents($acl_filename, "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\$config['acl'] = " . var_export($acl, true) . ';');
		}
		else
		{
			return false;
		}
	}
	
	public function update_acl($nca_ids, $role_ids)
	{
		if (is_array($role_ids))
		{
			$acl = $this->db->get_where('z_acl', ['nca_id' => $nca_ids])->result_array();
			$old_acl = [];
			foreach ($acl as $a)
			{
				$old_acl[] = $a['role_id'];
			}
			$acl_create = array_diff($role_ids, $old_acl);
			$acl_delete = array_diff($old_acl, $role_ids);
			foreach ($acl_create as $ac)
			{
				$this->db->insert('z_acl', ['nca_id' => $nca_ids, 'role_id' => $ac]);
			}
			foreach ($acl_delete as $ad)
			{
				$this->db->delete('z_acl', ['nca_id' => $nca_ids, 'role_id' => $ad]);
			}
		}
		else
		{
			$acl = $this->db->get_where('z_acl', ['role_id' => $role_ids])->result_array();
			$old_acl = array();
			foreach ($acl as $a)
			{
				$old_acl[] = $a['nca_id'];
			}
			$acl_create = array_diff($nca_ids, $old_acl);
			$acl_delete = array_diff($old_acl, $nca_ids);
			foreach ($acl_create as $ac)
			{
				$this->db->insert('z_acl', ['nca_id' => $ac, 'role_id' => $role_ids]);
			}
			foreach ($acl_delete as $ad)
			{
				$this->db->delete('z_acl', ['nca_id' => $ad, 'role_id' => $role_ids]);
			}
		}
		return ['ack' => true, 'msg' => ''];
	}
	
	public function update_nca_by_uid($uid)
	{
		$where = ['show' => 1, 'controller!=' => 'login'];
		$nca = $this->db->get_where('z_nca', $where)->result_array();
		$this_user_nca = [];
		foreach ($nca as $n)
		{
			$all_nca[$n['id']] = $n;
		}
		if ($uid == 1) // 超级管理员
		{
			foreach ($all_nca as $an)
			{
				if ($an['action'] == 'index')
				{
					$this_user_nca[$an['controller']]['name'] = $an['desc'];
					$this_user_nca[$an['controller']]['param'] = $an['param'];
				}
				$this_user_nca[$an['controller']]['child'][$an['action']]['name'] = $an['desc'];
			}
		}
		else
		{
			$acl = $this->db->query('SELECT z_acl.nca_id FROM z_acl LEFT JOIN z_user_role ON z_user_role.role_id=z_acl.role_id WHERE z_user_role.user_id=?', [$uid])->result_array();
			$this_user_actions = array_intersect_key($all_nca, array_flip(array_column($acl, 'nca_id')));
			foreach ($this_user_actions as $tua)
			{
				if ($tua['action'] == 'index')
				{
					$this_user_nca[$tua['controller']]['name'] = $tua['desc'];
					$this_user_nca[$an['controller']]['param'] = $an['param'];
				}
				$this_user_nca[$tua['controller']]['child'][$tua['action']]['name'] = $tua['desc'];
			}
		}
		if ($this->cache->memcached->is_supported())
		{
			$this->cache->memcached->save(Z_NCA.$uid, $this_user_nca, 100);
		}
	}
}