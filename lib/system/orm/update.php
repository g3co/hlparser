<?php

/** Класс для обновления данных в БД
 * Created by PhpStorm.
 * User: Валера
 * Date: 29.07.2016
 * Time: 0:20
 */
class system_orm_update extends system_orm_base implements implements_orm{
    protected $conditions = null;

	/** Выбор таблицы
	 * @param string $table имя таблицы в БД
     * @return system_orm_update
	 */
	function setTable(string $table) {
		$this->table = $table;
        return $this;
	}

	/** Установка значений для обновления
	 * @param array $values значения для вставки в формате 'имя колонки' => 'значение'
     * @return system_orm_update
	 */
	function setValues(array $values) {
		$this->values = $values;
        return $this;
	}

    /** Установка условий
     * @param array $values значения для построения условия обновления 'имя колонки' => 'значение'
     * @return system_orm_update
     */
    function setConditions(array $values) {
        $this->conditions = $values;
        return $this;
    }

	/** Выполнить запрос update
	 * @return int возвращает AI последнего вставленного элемента
	 */
	function do(){
		$updates = [];

		foreach($this->values as $key=>$value){
			if($value === NULL){
				$valuesString =   'NULL';
			}else{
				$valuesString =   '"' . addslashes($value) . '"';
			}

            $updates[]   =   '`' . addslashes($key) . '` = ' . $valuesString;
		}

        $conditions = array();

        foreach($this->conditions as $key=>$value){
            $conditions[]   =   '`' . addslashes($key) . '` = \'' . $value . '\'';
        }

        lib_database::getInstance()->query("UPDATE `" . $this->table . "` SET " . implode(',',$updates) . " WHERE " . implode(' AND ', $conditions));

        return true;
	}
}