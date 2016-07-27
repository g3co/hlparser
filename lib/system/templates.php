<?php

/**
 * Class system_templates
 */
class system_templates {
	/** Преобразует свойства класса в массив
	 * @return array массив свойств
	 */
	public function compileFields(){
		return get_object_vars($this);
	}

	/**
	 * @return array {
	 *     @var string $keys Строка с ключами
	 *     @var string $values  Строка со значениями
	 * }
	 */
	public function prepareForQuery(){
		$valuesString = array();
		$keysString = array();

		foreach($this->compileFields() as $key=>$value){
			$valuesString[] =   '"' . addslashes($value) . '"';
			$keysString[]   =   '`' . addslashes($key) . '`';
		}

		return ['keys' => implode(', ', $keysString), 'values' => implode(', ', $valuesString)];
	}
}