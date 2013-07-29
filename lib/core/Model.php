<?php
/**
 * @option:	基础类-Model类
 * @author:	bishenghua
 * @date:	2013/07/23
 * @email:	bsh@ojo.cc
 */

namespace lib\core;

class Model {
	/**
	 * 主库实例
	 * @var unknown
	 */
	private static $_dbMaster = null;
	
	/**
	 * 从库实例
	 * @var unknown
	 */
	private static $_dbSlave = null;
	
	/**
	 * 启用主库替换从库标示
	 * @var unknown
	 */
	private static $_useMaster = false;
	
	/**
	 * 连接从库次数
	 * @var unknown
	 */
	private static $_slaveTryTime = 1;
	
	/**
	 * 主库配置
	 * @var unknown
	 */
	private static $_configMaster = null;
	
	/**
	 * 从库配置
	 * @var unknown
	 */
	private static $_configSlave = null;
	
	/**
	 * 字符集
	 * @var unknown
	 */
	private static $_charset = 'utf8';
	
	/**
	 * 表前缀
	 * @var unknown
	 */
	private static $_prefix = '';
	
	public function __construct() {
		
	}
	
	/**
	 * 获取全部结果集
	 * @param unknown $sql
	 */
	protected function fetchAll($sql) {
		$stmt = self::getDbSlave()->prepare($sql);
		$flag = $stmt->execute();
		if ($flag) return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return array();
	}
	
	/**
	 * 获取一个结果集
	 * @param unknown $sql
	 * @return mixed
	 */
	protected function fetchOne($sql) {
		$stmt = self::getDbSlave()->prepare($sql);
		$flag = $stmt->execute();
		if ($flag) return $stmt->fetch(\PDO::FETCH_ASSOC);
		return array();
	}
	
	/**
	 * 插入操作
	 * @param unknown $table
	 * @param unknown $data
	 * @param string $insertId
	 * @return number
	 */
	protected function insert($table, $data, $insertId = true) {
		$dbh = self::getDbMaster();
		$keys = array_keys($data);
		$values = array_values($data);
		$fields = '`' . implode('`,`', $keys) . '`';
		$placeHolders = implode(',', array_fill(0, count($keys), '?'));
		$table = self::$_prefix . $table;
		$stmt = $dbh->prepare("INSERT INTO `$table`($fields) VALUES($placeHolders)");
		$flag = $stmt->execute($values);
		if (!$flag) return 0;
		if ($insertId) return $dbh->lastinsertid();
		return $stmt->rowCount();
	}
	
	/**
	 * 更新操作
	 * @param unknown $table
	 * @param unknown $data
	 * @param unknown $where
	 * @return number
	 */
	protected function update($table, $data, $where) {
		$dbh = self::getDbMaster();
		$set = $comma = '';
		foreach ($data as $key => $value) {
			$set .= "$comma`$key`='$value'";
			$comma = ',';
		}
		$table = self::$_prefix . $table;
		$stmt = $dbh->prepare("UPDATE `$table` SET $set WHERE $where");
		$flag = $stmt->execute();
		if (!$flag) return 0;
		return $stmt->rowCount();
	}
	
	/**
	 * 删除操作
	 * @param unknown $table
	 * @param unknown $where
	 * @return number
	 */
	protected function delete($table, $where) {
		$dbh = self::getDbMaster();
		$table = self::$_prefix . $table;
		$stmt = $dbh->prepare("DELETE FROM `$table` WHERE $where");
		$flag = $stmt->execute();
		if (!$flag) return 0;
		return $stmt->rowCount();
	}
	
	/**
	 * 开启事务
	 */
	protected function beginTransaction() {
		self::useMaster();
		self::getDbMaster()->beginTransaction();
	}
	
	/**
	 * 提交事务
	 */
	protected function commit() {
		self::useMaster();
		self::getDbMaster()->commit();
	}
	
	/**
	 * 回滚事务
	 */
	protected function rollBack() {
		self::useMaster();
		self::getDbMaster()->rollBack();
	}
	
	/**
	 * 获取表前缀
	 * @return Ambigous <string, \lib\core\unknown>
	 */
	protected static function getPrefix() {
		return self::$_prefix ? self::$_prefix : '';
	}
	
	/**
	 * 强制使用主库
	 */
	protected static function useMaster() {
		self::$_useMaster = true;
	}
	
	/**
	 * 获取主库连接实例
	 */
	private static function getDbMaster() {
		if (self::$_dbMaster === null) {
			try {
				//加载主库配置
				if (self::$_configMaster === null) {
					self::$_configMaster = Config::database();
					self::$_charset = self::$_configMaster['charset'];
					self::$_prefix = self::$_configMaster['prefix'];
					self::$_configMaster = self::$_configMaster['master'];
				}
				
				self::$_dbMaster = new \PDO(self::$_configMaster['dsn'], self::$_configMaster['username'], self::$_configMaster['password'], self::$_configMaster['option']);
				self::$_dbMaster->setAttribute(\PDO::ATTR_ERRMODE,  \PDO::ERRMODE_EXCEPTION);
				self::$_dbMaster->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的仿真效果, 这可以确保SQL语句和相应的值在传递到mysql服务器之前是不会被PHP解析的（禁止了所有可能的恶意SQL注入攻击
			
				self::$_dbMaster->exec('SET CHARACTER SET ' . self::$_charset);
				//self::$_dbMaster = null;  //断开连接
			} catch (\PDOException $e) {
				self::$_dbMaster = null;
			}
		}
		return self::$_dbMaster;
	}
	
	/**
	 * 获取从库连接实例
	 */
	private static function getDbSlave() {
		if (self::$_useMaster) return self::getDbMaster();
		
		if (self::$_dbSlave === null) {
			try {
				//加载从库配置
				if (self::$_configSlave === null) {
					self::$_configSlave = Config::database();
					self::$_charset = self::$_configSlave['charset'];
					self::$_prefix = self::$_configSlave['prefix'];
					self::$_configSlave = self::$_configSlave['slave'];
				}
				$slaveNum = count(self::$_configSlave); //从服务器个数
				$selectNum = mt_rand(0, $slaveNum - 1); //随机取一个从服务器
				$configSlave = self::$_configSlave[$selectNum];
				
				self::$_dbSlave = new \PDO($configSlave['dsn'], $configSlave['username'], $configSlave['password'], $configSlave['option']);
				self::$_dbSlave->setAttribute(\PDO::ATTR_ERRMODE,  \PDO::ERRMODE_EXCEPTION);
				self::$_dbSlave->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的仿真效果
					
				self::$_dbSlave->exec('SET CHARACTER SET ' . self::$_charset);
				//self::$_dbSlave = null;  //断开连接
			} catch (\PDOException $e) {
				//排除连接失败的从服务器
				unset(self::$_configSlave[$selectNum]);
				self::$_configSlave = array_values(self::$_configSlave);
				
				self::$_dbSlave = null; //清空前面实例化的类
				if (self::$_slaveTryTime < $slaveNum) {
					self::$_slaveTryTime++; //连接失败记录次数
					return self::getDbSlave(); //重新获取从库实例
				}
			}
		}
		return self::$_dbSlave;
	}
}