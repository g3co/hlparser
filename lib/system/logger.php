<?php

class system_logger {
	/**
	 * @var system_logger $_instance
	 */
	private static $_instance = null;
	static $arrErrors = array();

	private function __construct() {
	}

	static function addErrorLog(string $error){
		array_push(system_logger::$arrErrors, $error);
	}

	static function getErrors(bool $makeString = false){
		$tmpErrors = system_logger::$arrErrors;

		if(empty($tmpErrors)) return false;

		if($makeString){
			return print_r(system_logger::$arrErrors,true);
		}else{
			return system_logger::$arrErrors;
		}
	}

	/**
	 * @return system_logger
	 */
	static public function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}