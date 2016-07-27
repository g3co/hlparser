<?php
class system_database {
	/**
	 * @var mysqli $_instance
	 */
	private static $_instance = null;

	private function __construct() {
	}

	public function connect($host, $username, $passwd, $dbname){
		self::$_instance = new mysqli($host, $username, $passwd, $dbname);
		self::$_instance->set_charset('utf8');
	}


	/**
	 * @return mysqli
	 */
	static public function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}