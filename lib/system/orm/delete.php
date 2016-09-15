<?php

/** Класс для удаления данных в БД
 * Created by PhpStorm.
 * User: Валера
 * Date: 29.07.2016
 * Time: 0:20
 */
class system_orm_delete extends system_orm_base implements implements_orm{

	/** Выбор таблицы
	 * @param string $table имя таблицы в БД
     * @return system_orm_delete
	 */
	function setTable(string $table) {
		$this->table = $table;
        return $this;
	}

    /** Установка условий выборки
     * @param array $values значения для построения условия выборки 'имя колонки' => 'значение'
     * @return system_orm_delete
     */
    function setConditions(array $values) {
        $this->values = $values;
        return $this;
    }

	/** Выполнить запрос delete
	 * @return boolean
	 */
	function do(){
        $conditions = array();

        foreach($this->values as $key=>$value){
            $conditions[]   =   '`' . addslashes($key) . '` = \'' . $value . '\'';
        }

        $sql = "DELETE FROM `" . $this->table . "` WHERE " . implode(' AND ', $conditions);

        lib_database::getInstance()->query($sql);

        return true;
	}
}