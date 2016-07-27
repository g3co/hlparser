<?php
/**
 * @package		SiteMapGenerator
 * @subpackage          ����������� � ���� ������
 * @copyright           Copyright (C) 2013 Romantic-Toys.ru
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
$host = 'localhost';
$username = 'honeylovers';
$passwd = '7mPJ6xMS3LW003Zv';
$dbname = 'honeylovers';

class lib_database {
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

$dbConnect = lib_database::getInstance();
$dbConnect->connect($host, $username, $passwd, $dbname);



function QueryGet($sql, $mode = MYSQLI_ASSOC){
	$result = lib_database::getInstance()->query($sql);
	$output = $result->fetch_all($mode);
	if(isset($output)) return $output;
}

function QueryExec($sql,$debug=false,$debugLevel=1){
	return lib_database::getInstance()->query($sql);
}
