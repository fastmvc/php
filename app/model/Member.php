<?php
/**
 * @option:	模块类
 * @author:	bishenghua
 * @date:	2013/07/23
 * @email:	bsh@ojo.cc
 */

namespace app\model;
use lib\core\Model;

class Member extends Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getMember() {
		//Model::useMaster();
		//print_r($this->fetchAll('select user_id,user_name,email from ecm_member'));
	}
	
	public function getMemberOne() {
		//Model::useMaster();
		print_r($this->fetchOne('select user_id,user_name,email from ecm_member'));
	}
}