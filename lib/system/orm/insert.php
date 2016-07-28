<?php

/**
 * Created by PhpStorm.
 * User: Валера
 * Date: 29.07.2016
 * Time: 0:20
 */
class system_orm_insert extends system_orm_base implements implements_orm{
	function setTable(string $table) {
		$this->table = $table;
	}

	function setValues(array $values) {
		$this->values = $values;
	}

	function do(){
		$valuesString = array();
		$keysString = array();

		foreach($this->values as $key=>$value){
			if($value === NULL){
				$valuesString[] =   'NULL';
			}else{
				$valuesString[] =   '"' . addslashes($value) . '"';
			}

			$keysString[]   =   '`' . addslashes($key) . '`';
		}

		echo "INSERT INTO `" . $this->table . "` (". implode(',',$keysString) .") VALUES (". implode(',',$valuesString) .")";
	}
}