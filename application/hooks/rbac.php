<?php
class Rbac
{
	private $CI;
	private $URI;
	private $ACL;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->URI = array(
			'namespace' => strtolower($this->CI->router->fetch_directory()),
			'controller' => strtolower($this->CI->router->fetch_class()),
			'action' => strtolower($this->CI->router->fetch_method())
		);
	}
	
	public function acl()
	{
		//校检是否存在acl.php权限文件
		if (! file_exists(APPPATH.'config/acl.php')) $this->_refreshACL();
		$this->CI->config->load('acl');
		$this->ACL = $this->CI->config->item('acl');
		if (empty($this->ACL)) $this->_refreshACL();

		//包含qeephp框架的acl.php权限控制文件
		$this->CI->load->library('acl');
		get_class(new ACL());
		$roles = empty($this->CI->session->userdata['yyft']['userid']) ? '' : $this->CI->session->userdata['yyft']['userid'];
		
		$this->CI->config->load('acl_bypass', true);
		$skip_acl = $this->CI->config->item('acl_bypass');
		if (!$this->_authorized($roles, $this->URI) && !$this->_skipACL($this->URI, $skip_acl))
        {
            $this->_on_access_denied($roles);
        }
	}
	
	protected function _refreshACL()
	{
		$this->CI->load->model('Nca_model');
		$result = $this->CI->Nca_model->refreshACL();
		if (empty($result)) show_error('刷新权限文件失败，请联系技术人员');
	}
	
	protected function _skipACL($uri, $skip_acl)
	{
		if (in_array($uri['controller'], $skip_acl['controller'])) return true;
		if (isset($skip_acl['action'][$uri['controller']]) && $skip_acl['action'][$uri['controller']] == $uri['action']) return true;
	}
	
	protected function _authorized($roles, $uri)
	{
		$roles = normalize($roles);
		$controller_acl = $this->_controllerACL($uri, $this->ACL);
		$action_name = $uri['action'];
		if (isset($controller_acl['actions'][ACL::ALL_ACTIONS]))
        {
            // 如果为所有动作指定了默认 ACT，则使用该 ACT 进行检查
         	if (ACL::rolesBasedCheck($roles, $controller_acl['actions'][ACL::ALL_ACTIONS]))
            {
            	return true;
            }
        }
    	if (isset($controller_acl['actions'][$action_name]))
        {
            // 如果动作的 ACT 检验通过，则忽略控制器的 ACT
        	return ACL::rolesBasedCheck($roles, $controller_acl['actions'][$action_name]);
        }
        // 否则检查是否可以访问指定控制器
        return ACL::rolesBasedCheck($roles, $controller_acl);
	}
	
	/**
     * 获得指定控制器的 ACL
     * @param string|array $uri
     * @return array
     */
	protected function _controllerACL($uri = array(), $acl = array())
	{
		if (isset($acl[$uri['controller']]))
		{
			$acl = array_change_key_case($acl, CASE_LOWER);
			return (array)$acl[$uri['controller']];
		}
		return isset($acl[ACL::ALL_CONTROLLERS]) ? (array)$acl[ACL::ALL_CONTROLLERS] : array('allow' => ACL::ACL_EVERYONE);
	}
	
	protected function _on_access_denied($roles)
	{
		if (empty($roles)) redirect(site_url('login/index'));
		$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('login/index');
		show_error('当前用户没有访问这个页面的权限，<a href="' . $redirect_url . '">点击返回</a>');exit;
	}
	
	protected function _db_error()
	{
		show_error('DB_error()');exit;
	}
}