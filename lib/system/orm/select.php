<?php

/** Класс для получения данных из БД
 * Created by PhpStorm.
 * User: Валера
 * Date: 29.07.2016
 * Time: 0:20
 */
class system_orm_select extends system_orm_base implements implements_orm{
	protected $output = array();

	/** Выбор таблицы
	 * @param string $table имя таблицы в БД
	 */
	function setTable(string $table) {
		$this->table = $table;
	}

	/** Установка условий выборки
	 * @param array $values значения для построения условия выборки 'имя колонки' => 'значение'
	 */
	function setConditions(array $values) {
		$this->values = $values;
	}

	/** Установка возвращаемых полей
	 * @param string $output значения для построения условия выборки 'имя колонки' => 'значение'
	 */
	function setOutputValues(string $output) {
		$this->output = $output;
	}

	/** Выполнить запрос SELECT
	 * @return int возвращает результат запроса
	 */
	function do($debug = false){
		$conditions = array();

		foreach($this->values as $key=>$value){
			$conditions[]   =   '`' . addslashes($key) . '` = \'' . $value . '\'';
		}

		$sql = "SELECT * FROM `" . $this->table . "` WHERE " . implode(' AND ', $conditions);
		if($debug) echo $sql . "\n\n";

		if(!empty($this->output)){
			$result = lib_database::getInstance()->query($sql)->fetch_assoc();
			$result = $result[$this->output];
		}else{
			$result = lib_database::getInstance()->query($sql)->fetch_all(MYSQLI_ASSOC);
		}

		return $result;
	}
}