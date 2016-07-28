<?php
class system_orm {

	private static $_instance = null;
	protected $db = NULL;

	private function __construct() {
		$this->db = system_database::getInstance();
	}

	public function insert(){
		return new system_orm_insert;
	}

	static public function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}