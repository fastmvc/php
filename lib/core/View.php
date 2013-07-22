<?php
/**
 * @option:	基础类-视图
 * @author:	bishenghua
 * @date:	2013/07/22
 * @email:	bsh@ojo.cc
 */

namespace lib\core;
require SYSTEM_LIB . 'smarty/Smarty.class.php';

class View extends \Smarty {
	
	public function __construct($config) {
		parent::__construct();
		
		$this->debugging = $config['debug'];
		$this->caching = $config['caching'] ? $config['caching'] : \Smarty::CACHING_LIFETIME_CURRENT;
		$this->cache_lifetime = $config['cache_lifetime'];
		$this->setTemplateDir(SYSTEM_VIEW);
		$this->setCompileDir(SYSTEM_TEMP . 'templates_c/');
		$this->setConfigDir(SYSTEM_CONFIG . 'tpl/');
		$this->setCacheDir(SYSTEM_TEMP . 'cache/');
		$this->addPluginsDir(SYSTEM_LIB . 'smarty/appplugins/');
		$this->addTemplateDir(SYSTEM_VIEW . 'common/');
	}
}