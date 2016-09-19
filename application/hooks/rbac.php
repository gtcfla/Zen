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
		$roles = isset($_SESSION[SESSION_KEY]['uid']) ? $_SESSION[SESSION_KEY]['uid'] : NULL;
		
		//记录访问日志
		SeasLog::info($roles . ' | ' . $_SERVER['REMOTE_ADDR'] . ' | ' . $_SERVER['REQUEST_METHOD'] . ' | ' . $_SERVER['REQUEST_URI'] . ' | ' . json_encode($_POST));
		
		if (!$this->_authorized($roles, $this->URI))
        {
            $this->_on_access_denied($roles);
        }
	}
	
	protected function _refreshACL()
	{
		$this->CI->load->model('Nca_model');
		$result = $this->CI->Nca_model->refreshACL();
		if (empty($result)) show_error('权限失效');
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
		if (empty($roles)) redirect('login/index');
		show_error('访问权限不足');exit;
	}
	
	protected function _db_error()
	{
		show_error('db error');exit;
	}
}