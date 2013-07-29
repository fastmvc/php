<?php
/**
 * @option:	模块类
 * @author:	bishenghua
 * @date:	2013/07/23
 * @email:	bsh@ojo.cc
 */

namespace app\model;
use lib\core\Model;
use lib\core\Exception;

class Member extends Model {
	
	private $_prefix = '';
	
	public function __construct() {
		parent::__construct();
		$this->_prefix = self::getPrefix();
	}
	
	public function getMember() {
		//Model::useMaster();
		print_r($this->fetchAll('SELECT id,name,add_time FROM ' . $this->_prefix . 'user'));
		print_r($this->fetchOne('SELECT id,name,add_time FROM ' . $this->_prefix . 'user'));
		
		$data = array('name'=>'t1','add_time'=>'123456789');
		//echo $this->insert('user', $data);
		
		$data = array('name'=>'bishenghua1','add_time'=>'1234567898');
		$where = 'id=1';
		echo $this->update('user', $data, $where);
		
		echo $this->delete('user', 'id=15');
		
		//处理事务
		try {
			$this->beginTransaction();
			$data = array('name'=>'t1','add_time'=>'123456789');
			echo $this->insert('user', $data);
			$this->commit();
		} catch (Exception $e) {
			$this->rollBack();
		}
		
	}
	
}