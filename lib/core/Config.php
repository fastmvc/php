<?php
/**
 * @option:	基础类-配置文件
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Config {
	protected static $_config = array();
	protected static $_configName = array('base', 'smarty');
	
	public static function __callstatic($name, $arguments) {
		in_array($name, self::$_configName) &&
		!isset(self::$_config[$name]) && 
		self::$_config[$name] = include SYSTEM_CONFIG . "$name.php";
		return self::$_config[$name] ? self::$_config[$name] : array();
	}
}