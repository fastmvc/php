<?php
/**
 * @option:	自动加载类
 * @author:	bishenghua
 * @date:	2013/07/19
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

spl_autoload_register(__NAMESPACE__.'\Autoloader::loader');

class Autoloader {
	
	public static function loader($className) {
		$thisClass = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
		$baseDir = realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../') . DIRECTORY_SEPARATOR;
		
		if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
			$baseDir = substr($baseDir, 0, -strlen($thisClass));
		}
		
		$realClassName = $className = ltrim($className, '\\');
		
		$fileName = $baseDir;
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
		
		//echo $fileName,"\n";
		
		if (file_exists($fileName)) {
			require $fileName;
			if (!class_exists($realClassName, false)) throw new Exception('Class is not exists');
		} else throw new Exception('File is not exists');
	}
}