<?php

/** Класс для получения данных из БД
 * Created by PhpStorm.
 * User: Валера
 * Date: 29.07.2016
 * Time: 0:20
 */
class system_orm_select extends system_orm_base implements implements_orm{

	/** Выбор таблицы
	 * @param string $table имя таблицы в БД
	 */
	function setTable(string $table) {
		$this->table = $table;
	}

	/** Установка значений для вставки
	 * @param array $values значения для вставки в формате 'имя колонки' => 'значение'
	 */
	function setValues(array $values) {
		$this->values = $values;
	}

	/** Выполнить запрос insert
	 * @return int возвращает AI последнего вставленного элемента
	 */
	function do($debug = false){
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
		if($debug) echo "INSERT INTO `" . $this->table . "` (". implode(',',$keysString) .") VALUES (". implode(',',$valuesString) .")\n\n";

		lib_database::getInstance()->query("INSERT INTO `" . $this->table . "` (". implode(',',$keysString) .") VALUES (". implode(',',$valuesString) .")");
		return lib_database::getInstance()->insert_id;
	}
}