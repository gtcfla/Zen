<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('regex_nca'))
{
	/**
	 * 正则匹配文件内容
	 *
	 * @param	string	$dir
	 * @param	array	$regex
	 * @return	array
	 */
	function regex_nca($file, $regex)
	{
		$matchs = [];
		$file_content = php_strip_whitespace($file);
		foreach ($regex as $r)
		{
			preg_match_all($r, $file_content, $match);
			if (isset($match[1][0]))
			{
				$matchs[$r] = $match[1][0];
			}
		}
		return $matchs;
	}
}