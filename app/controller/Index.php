<?php
namespace app\controller;
use lib\core\Controller;

class Index extends Controller {
	
	public function __construct() {
		//$this->disabledView();
	}
	
	public function index() {
		echo 'controller:',$this->_controller,"<br>\naction:",$this->_action,"<br>\nparam:";
		print_r($this->_param);
		//var_dump($this->_view);
		$this->_view->assign('ok', 'good');
	}
}