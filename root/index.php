<?php
/**
 * @option:	入口文件
 * @author:	bishenghua
 * @date:	2013/0718
 * @email:	bsh@ojo.cc
 */

ini_set('display_errors', 'on');error_reporting(E_ALL^E_NOTICE^E_WARNING^E_STRICT);

//定义常量
define('APP_NAME', 'app');
define('LIB_NAME', 'lib');
define(SYSTEM_ROOT, dirname(dirname(__FILE__)) . '/');
define(SYSTEM_LIB, SYSTEM_ROOT . LIB_NAME . '/');
define(SYSTEM_APP, SYSTEM_ROOT . APP_NAME . '/');
define(SYSTEM_CONFIG, SYSTEM_APP . 'config/');
define(SYSTEM_MODEL, SYSTEM_APP . 'model/');
define(SYSTEM_VIEW, SYSTEM_APP . 'view/');
define(SYSTEM_TEMP, SYSTEM_APP . 'temp/');

//加载自动加载类功能
require SYSTEM_LIB . 'core/Autoloader.php';

//启动系统
use lib\core\Run;
Run::start();