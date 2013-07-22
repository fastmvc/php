<?php
/**
 * @option:	基础类-控制器
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Controller {
	protected $_controller = null;
	protected $_action = null;
	protected $_param = null;
	protected $_view = null;
	
	public function __construct() {
		
	}
	
	public function setController($controller) {
		$this->_controller = $controller;
	}
	
	public function setAction($action) {
		$this->_action = $action;
	}
	
	public function setParam($param) {
		$this->_param = $param;
	}
	
	public function setView() {
		if ($this->_view === false) return false;
		
		$this->_view = new View(Config::smarty());
		$this->_view->addTemplateDir(SYSTEM_VIEW . $this->_controller . '/');
		return true;
	}
	
	public function disabledView() {
		$this->_view = false;
	}
	
	public function display() {
		if ($this->_view === false) return false;
		
		$this->_view->display($this->_action . '.html');
	}
}