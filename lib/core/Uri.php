<?php
/**
 * @option:	基础类-Uri处理类
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Uri {
	public static $controller = null;
	public static $action = null;
	public static $param = null;
	
	public static function getController() {
		self::$controller === null && self::dealUri();
		return self::$controller;
	}
	
	public static function getAction() {
		self::$action === null && self::dealUri();
		return self::$action;
	}
	
	public static function getParam() {
		self::$param === null && self::dealUri();
		return self::$param;
	}
	
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
					foreach ($_GET as $key => $value) {
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
			}
		}
		//这里可以对参数进行过滤
		self::dealParam();
	}
	
	private static function dealParam() {
		//do self::$param;
	}
}