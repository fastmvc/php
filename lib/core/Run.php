<?php
/**
 * @option:	基础类-启动类
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Run {
	
	public static function start() {
		$controller = Uri::getController();
		$action = Uri::getAction();
		$param = Uri::getParam();
		$class = 'app\controller\\' . ucfirst($controller);
		try {
			$controllerObj = new $class;
			$controllerObj->setController($controller);
			$controllerObj->setAction($action);
			$controllerObj->setParam($param);
			$controllerObj->setView();
			if (method_exists($controllerObj, $action)) {
				$controllerObj->$action();
				$controllerObj->display();
			} else throw new Exception('Action is not exists');
		} catch (Exception $e) {}
		// else throw new Exception('File is not exists');
	}
}