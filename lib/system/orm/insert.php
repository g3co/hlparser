<?php

/** Класс для вставки данных в БД
 * Created by PhpStorm.
 * User: Валера
 * Date: 29.07.2016
 * Time: 0:20
 */
class system_orm_insert extends system_orm_base implements implements_orm{

	/** Выбор таблицы
	 * @param string $table имя таблицы в БД
     * @return system_orm_insert
	 */
	function setTable(string $table) {
		$this->table = $table;
        return $this;
	}

	/** Установка значений для вставки
	 * @param array $values значения для вставки в формате 'имя колонки' => 'значение'
     * @return system_orm_insert
	 */
	function setValues(array $values) {
		$this->values = $values;
        return $this;
	}

	/** Выполнить запрос insert
	 * @return int возвращает AI последнего вставленного элемента
	 */
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

		lib_database::getInstance()->query("INSERT INTO `" . $this->table . "` (". implode(',',$keysString) .") VALUES (". implode(',',$valuesString) .")");
		return lib_database::getInstance()->insert_id;
	}
}