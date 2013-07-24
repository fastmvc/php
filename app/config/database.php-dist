<?php
/**
 * @option:	数据库配置文件(PDO连接方式，支持一主多从，从服务器随机取，连接失败则尝试其他从服务器，全失败则失败)
 * @author:	bishenghua
 * @date:	2013/07/23
 * @email:	bsh@ojo.cc
 */

return array(
		'master' => array(
				'dsn' 		=> 'mysql:host=211.88.253.15;port=33061;dbname=trade2cn',
				'username'	=> 'trade2cn',
				'password'	=> '123456',
				'charset'	=> 'utf8',
				'option'	=> null,
		),
		'slave' => array(
			array(
				'dsn' 		=> 'mysql:host=211.88.253.15;port=33062;dbname=trade2cn',
				'username'	=> 'trade2cn',
				'password'	=> '123456',
				'charset'	=> 'utf8',
				'option'	=> null,
			),
			array(
				'dsn' 		=> 'mysql:host=211.88.253.15;port=33062;dbname=trade2cn',
				'username'	=> 'trade2cn',
				'password'	=> '123456',
				'charset'	=> 'utf8',
				'option'	=> null,
			),
		),
);