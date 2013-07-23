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
	
	public function __construct() {
		
	}
	
	/**
	 * 获取全部结果集
	 * @param unknown $sql
	 */
	protected function fetchAll($sql) {
		$stmt = self::getDbSlave()->query($sql);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 * 获取一个结果集
	 * @param unknown $sql
	 * @return mixed
	 */
	protected function fetchOne($sql) {
		$stmt = self::getDbSlave()->query($sql);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
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
					self::$_configMaster = self::$_configMaster['master'];
				}
				
				self::$_dbMaster = new \PDO(self::$_configMaster['dsn'], self::$_configMaster['username'], self::$_configMaster['password'], self::$_configMaster['option']);
				self::$_dbMaster->setAttribute(\PDO::ATTR_ERRMODE,  \PDO::ERRMODE_EXCEPTION);
			
				self::$_dbMaster->exec('SET CHARACTER SET ' . self::$_configMaster['charset']);
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
					self::$_configSlave = self::$_configSlave['slave'];
				}
				$slaveNum = count(self::$_configSlave); //从服务器个数
				$selectNum = mt_rand(0, $slaveNum - 1); //随机取一个从服务器
				$configSlave = self::$_configSlave[$selectNum];
				
				self::$_dbSlave = new \PDO($configSlave['dsn'], $configSlave['username'], $configSlave['password'], $configSlave['option']);
				self::$_dbSlave->setAttribute(\PDO::ATTR_ERRMODE,  \PDO::ERRMODE_EXCEPTION);
					
				self::$_dbSlave->exec('SET CHARACTER SET ' . $configSlave['charset']);
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