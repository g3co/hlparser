<?php

class system_base{
	protected $errors = array();

	public function db(){
		return system_database::getInstance();
	}

	public function orm(){
		return system_orm::getInstance();
	}

	protected function addErrorLog(string $error){
		$this->errors[] = $error;
	}

	public function getErrors(bool $makeString = false){
		if(empty($this->errors)) return false;

		if($makeString){
			return print_r($this->errors,true);
		}else{
			return $this->errors;
		}
	}
}