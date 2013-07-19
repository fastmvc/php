<?php
namespace app\controller;

use lib\core\Controller;

class Index extends Controller {
	
	public function __construct() {
		
	}
	
	public function index() {
		echo 'controller:',$this->controller,"<br>\naction:",$this->action,"<br>\nparam:";
		print_r($this->param);
	}
}