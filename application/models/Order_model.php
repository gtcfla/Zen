<?php
class Order_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 订单号生成器（利用Redis的INCR生成当天自增序号，例：O20160616000001，长度为13）
	 *
	 * @param 订单前缀 $prefix
	 * @param 订单自增序列下标$key
	 * @return int order_number
	 * @author Mr.Z <gtcfla@gmail.com> 2016年10月12日
	 */
	public function order_number_generator($prefix='O', $key='order_number')
	{
		$this->load->driver('cache');
		if (! $this->cache->redis->is_supported()) return;
			
		// 设置序列起始位
		if (! $this->cache->redis->get($key))
		{
			$sequence = $this->cache->redis->save($key, 1, 24*3600); // 缓存有效时间为一天
		}
		else
		{
			$sequence = $this->cache->redis->increment($key);
		}
		return $prefix.date('ymd').sprintf("%06d", $sequence);
	}
}