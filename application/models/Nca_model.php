<?php
class Nca_model extends CI_Model
{
	private $db;
	// NCA正则匹配表达式
	private $regex = ['/class\s(.+?)\sextends/i', '/public function\s([^_]+?)\(.+?\{(.+?)\}/', '/data\[\'title\'\]\s*=\s*\'(.+?)\'/'];
	
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
	}
	
	/**
	 * 正则匹配NCA
	 * @param 匹配目录 $dir
	 * @return return array
	 * @author zen <gtcfla@gmail.com> 2016年9月8日
	 */
	public function regexNca($dir)
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
				preg_match_all($this->regex[1], $file_content, $function);
				if(isset($function[1]))
				{
					foreach ($function[1] as $key => $action)
					{
						$action = strtolower($action);
						preg_match_all($this->regex[2], $function[2][$key], $title);
						$desc = empty($title[1]) ? '' : $title[1][0];
						$nca[$controller.'/'.$action] = [
							'controller' => $controller,
							'action' => $action,
							'desc' => $desc
						];
					}
				}
			}
		}
		return $nca;
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
	 * @author zen <gtcfla@gmail.com> 2016年9月6日
	 */
	public function __updateNca($nca)
	{
		foreach ($nca as $k => $n)
		{
			try
			{
				$this->db->insert('z_nca', $n);
			}
			catch (Exception $e)
			{
				seaslog::error($e->getMessage());
			}
		}
	}
}