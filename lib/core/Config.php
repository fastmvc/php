<?php
/**
 * @option:	基础类-配置类
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Config {
	/**
	 * 配置信息
	 * @var unknown
	 */
	protected static $_config = array();
	
	/**
	 * 运行加载的配置
	 * @var unknown
	 */
	protected static $_configName = array('base', 'smarty', 'database');
	
	/**
	 * 根据调用的函数加载函数名对应的配置
	 * @var unknown
	 */
	public static function __callstatic($name, $arguments) {
		in_array($name, self::$_configName) &&
		!isset(self::$_config[$name]) && 
		self::$_config[$name] = include SYSTEM_CONFIG . "$name.php";
		return self::$_config[$name] ? self::$_config[$name] : array();
	}
}