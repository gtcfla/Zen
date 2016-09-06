<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_all_actions'))
{
	function get_all_actions($controller_dir)
	{
		$file = array_flip(find_controller_files($controller_dir));
		return find_actions_in_files($file, $controller_dir);
	}
}

if ( ! function_exists('find_controller_files'))
{
	function find_controller_files($controller_dir)
	{
		$handle = opendir($controller_dir);
		$ctrl_files = array();
		while ($file = readdir($handle))
		{
			$filename = $controller_dir . '/' . $file;
			if ($file != '.' && $file != '..' && filetype($filename) == 'dir')
			{
				$sub_dir_files = find_controller_files($filename);
				$ctrl_files = array_merge($ctrl_files, $sub_dir_files);
			}
			elseif ($file != '.' && $file != '..' && substr(strtolower($file), -4) == '.php')
			{
				$ctrl_files[] = substr(str_replace($controller_dir, '', $controller_dir . '/' . $file), 1);;
			}
		}
		closedir($handle);
		return $ctrl_files;
	}
}

if ( ! function_exists('find_actions_in_files'))
{
	function find_actions_in_files($ctrl_files, $controller_dir)
	{
		$class_pattern = '/class\s(.+?)\sextends/i';
		$function_pattern = '/public function\s(.+?)\(.+?\{(.+?)\}/';
		$title_pattern = '/data\[\'title\'\]\s*=\s*\'(.+?)\'/';
		$nca = array();
		foreach ($ctrl_files as $filename => $val)
		{
			$file_content = php_strip_whitespace($controller_dir . '/' . $filename);
			preg_match_all($class_pattern, $file_content, $class_matched);
			if(isset($class_matched[1][0]))
			{
				$controller_name = strtolower($class_matched[1][0]);
				preg_match_all($function_pattern, $file_content, $function_matched);
				if(isset($function_matched[1]))
				{
					foreach ($function_matched[1] as $key => $action)
					{
						$action_name = strtolower($action);
						preg_match_all($title_pattern, $function_matched[2][$key], $title_matched);
						$desc = empty($title_matched[1]) ? '' : $title_matched[1][0];
						$nca[$controller_name . '_' . $action_name] = array(
							'controller' => $controller_name,
							'action' => $action_name,
							'descript' => $desc
						);
					}
				}
			}
		}
		return $nca;
	}
}