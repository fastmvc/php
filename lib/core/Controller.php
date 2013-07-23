<?php
/**
 * @option:	基础类-控制器
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Controller {
	/**
	 * 当前控制器名称
	 * @var unknown
	 */
	protected $_controller = null;
	
	/**
	 * 当前动作名称
	 * @var unknown
	 */
	protected $_action = null;
	
	/**
	 * 当前参数信息(包含get、post所有信息，参数一定要从这个变量中获取，避免直接使用GET和POST)
	 * @var unknown
	 */
	protected $_param = null;
	
	/**
	 * 视图实例，此次即为smarty对象
	 * @var unknown
	 */
	protected $_view = null;
	
	public function __construct() {
		
	}
	
	/**
	 * 设置控制器
	 * @param unknown $controller
	 */
	public function setController($controller) {
		$this->_controller = $controller;
	}
	
	/**
	 * 设置动作
	 * @param unknown $action
	 */
	public function setAction($action) {
		$this->_action = $action;
	}
	
	/**
	 * 设置参数
	 * @param unknown $param
	 */
	public function setParam($param) {
		$this->_param = $param;
	}
	
	/**
	 * 设置视图
	 * @return boolean
	 */
	public function setView() {
		if ($this->_view === false) return false;
		
		$this->_view = new View();
		$this->_view->addTemplateDir(SYSTEM_VIEW . $this->_controller . '/');
		return true;
	}
	
	/**
	 * 取消视图
	 */
	public function disabledView() {
		$this->_view = false;
	}
	
	/**
	 * 加载模板
	 * @return boolean
	 */
	public function display() {
		if ($this->_view === false) return false;
		
		$this->_view->display($this->_action . '.html');
	}
}