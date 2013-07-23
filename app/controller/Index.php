<?php
/**
 * @option:	控制器类
 * @author:	bishenghua
 * @date:	2013/07/19
 * @email:	bsh@ojo.cc
 */

namespace app\controller;
use lib\core\Controller;
use app\model\Member;

class Index extends Controller {
	
	public function __construct() {
		//$this->disabledView();
	}
	
	public function index() {
		echo 'controller:',$this->_controller,"<br>\naction:",$this->_action,"<br>\nparam:";
		print_r($this->_param);
		//var_dump($this->_view);
		$memberModel = new Member();
		$memberModel->getMember();
		$memberModel->getMemberOne();
		$this->_view->assign('ok', 'good');
	}
}