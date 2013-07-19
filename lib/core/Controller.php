<?php
/**
 * @option:	基础类-控制器
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Controller {
	public $controller = null;
	public $action = null;
	public $param = null;
	
	public function __construct() {
		
	}
	
	public function setController($controller) {
		$this->controller = $controller;
	}
	
	public function setAction($action) {
		$this->action = $action;
	}
	
	public function setParam($param) {
		$this->param = $param;
	}
}