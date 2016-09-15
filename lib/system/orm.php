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

	public function select(){
		return new system_orm_select;
	}

	public function delete(){
		return new system_orm_delete;
	}

	static public function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}