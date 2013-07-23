<?php
/**
 * @option:	基础类-Uri处理类
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Uri {
	/**
	 * 控制器名称
	 * @var unknown
	 */
	public static $controller = null;
	
	/**
	 * 动作名称
	 * @var unknown
	 */
	public static $action = null;
	
	/**
	 * 参数
	 * @var unknown
	 */
	public static $param = null;
	
	/**
	 * 获取控制器
	 * @return \lib\core\unknown
	 */
	public static function getController() {
		self::$controller === null && self::dealUri();
		return self::$controller;
	}
	
	/**
	 * 获取动作
	 * @return \lib\core\unknown
	 */
	public static function getAction() {
		self::$action === null && self::dealUri();
		return self::$action;
	}
	
	/**
	 * 获取参数
	 * @return \lib\core\unknown
	 */
	public static function getParam() {
		self::$param === null && self::dealUri();
		return self::$param;
	}
	
	/**
	 * 处理uri
	 */
	private static function dealUri() {
		$uri = $_SERVER['REQUEST_URI'];
		self::$controller = 'index';
		self::$action = 'index';
		self::$param = array();
		//判断地址中是否存在?符号
		if (($pos = strpos($uri, '?')) !== false) {
			//取问号后面的字符串
			$uri = substr($uri, $pos + 1);
			if ($uri) {
				//判断是否含有&符号
				if (($pos = strpos($uri, '&')) !== false) {
					//取开始到&符号之间的字符串
					$uri = substr($uri, 0, $pos);
				}
				//判断$_GET数组的key值中是否含有uri，如果含有说明是'/controller/action/param1/value1/param2/value2'方式
				//否则认为是GET方式
				if (array_key_exists($uri, $_GET)) {
					$uri = explode('/', $uri);
					self::$controller = $uri[0];
					$uri[1] && self::$action = $uri[1];
					if (($count = count($uri)) > 2) {
						for ($i = 2; $i < $count; $i+=2) {
							self::$param[$uri[$i]] = $uri[$i + 1];
						}
					}
				} else {
					//如果是GET方式，则认为其中必含c和a认为是controller和action，如果不含则认为是默认值
					self::addParam($_GET);
					if (isset($_POST)) {
						self::addParam($_POST);
					}
				}
			}
		}
		//这里可以对参数进行过滤
		self::dealParam();
	}
	
	/**
	 * 添加参数
	 * @param unknown $array
	 */
	private static function addParam(& $array) {
		foreach ($array as $key => $value) {
			if ($key == 'c') {
				self::$controller = $value;
				continue;
			}
			if ($key == 'a') {
				self::$action = $value;
				continue;
			}
			self::$param[$key] = $value;
		}
	}
	
	/**
	 * 过滤参数
	 */
	private static function dealParam() {
		//do self::$param;
	}
}