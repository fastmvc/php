<?php
/**
 * @option:	基础类-异常类
 * @author:	bishenghua
 * @date:	2013/07/18
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Exception extends \Exception {
	
	public function __construct($e) {
		echo $e;
	}
}