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
     * @return system_orm_select
	 */
	function setTable(string $table) {
		$this->table = $table;
        return $this;
	}

	/** Установка условий выборки
	 * @param array $values значения для построения условия выборки 'имя колонки' => 'значение'
     * @return system_orm_select
	 */
	function setConditions(array $values) {
		$this->values = $values;
        return $this;
	}

	/** Установка возвращаемых полей
	 * @param string $output значения для построения условия выборки 'имя колонки' => 'значение'
     * @return system_orm_select
	 */
	function setOutputValues(string $output) {
		$this->output = $output;
        return $this;
	}

	/** Выполнить запрос SELECT
	 * @return array возвращает результат запроса
	 */
	function do(){
        $where = '';

        if(!is_null($this->values)){
            $conditions = array();

            foreach($this->values as $key=>$value){
                $method = "=";
                if(is_array($value)){
                    $method = $value[0];
                    $value = $value[1];
                }
                $conditions[]   =   '`' . addslashes($key) . '` ' . $method . ' \'' . $value . '\'';
            }

            if(!empty($conditions)) $where = " WHERE " . implode(' AND ', $conditions);
        }

        $sql = "SELECT * FROM `" . $this->table . "`" . $where;

		if(!empty($this->output)){
			$result = lib_database::getInstance()->query($sql)->fetch_assoc();
			$result = $result[$this->output];
		}else{
			$result = lib_database::getInstance()->query($sql)->fetch_all(MYSQLI_ASSOC);
		}

		return $result;
	}
}