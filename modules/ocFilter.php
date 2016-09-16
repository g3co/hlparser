<?php
/**
 * Description of ocFilter
 *
 * @author PHP-1
 */
class ocFilter extends system_base {
	public $values = array();
	public $counter = 0;
	protected $first = NULL;
	protected $loadedOptions = array();

	/** Добавление значений в фильтры
	 * @param array $value массив значений атрибута (id, name)
	 * @param integer $id ид товара
	 * @param integer $category ид категории
	 */
	public function set($value, $id, $category) {
		$valueName = mb_strtolower($value[ 'value' ], "utf-8");

		/** Секция записи атрибутов
		 *  в фильтр. Только при первом прогоне
		 */
		if(!in_array($value[ 'id' ],$this->loadedOptions)){
			$this->loadedOptions[] = $value[ 'id' ];
			$this->setAtribute($value);
		}

		/** перебор диапазона размеров, чтобы получить реальные размеры диапазона
		 * например 42-48 это 42, 44, 46, 48 размеры.
		 */

		if ($value[ 'id' ] == config::RUSIZE_ATRIBUTE_ID) {
			$rusizeExplode = explode('-', $valueName);

			if (count($rusizeExplode) > 1) {
				$start = $rusizeExplode[ 0 ];
				$stop = $rusizeExplode[ 1 ];

				for ($i = 2; $start <= $stop; $start += $i) {
                    $this->setRelations($start, $value, $id, $category);
				}
			}
		} else {
			$this->setRelations($valueName, $value, $id, $category);
		}
	}

	/** Метод устанавливает зависимости между фильтром и элементами каталога
	 * @param string $valueName имя значения
	 * @param array $value массив значений атрибута (id, name)
	 * @param integer $id ид товара
	 * @param integer $category ид категории
	 */
	protected function setRelations($valueName, $value, $id, $category){
		$optionId = $value[ 'id' ];

		$insertOptionValueDescription = $this->orm()->insert();
		$insertOptionValueDescription->setTable('oc_ocfilter_option_value_description');
		$insertOptionValueDescription->setValues(array (
			'value_id'=>NULL,
			'option_id' => $optionId,
			'language_id' => 1,
			'name' => $valueName
		));
		$value_id = $insertOptionValueDescription->do();

		if ($value_id) {

			$insertOptionValue = $this->orm()->insert();
			$insertOptionValue->setTable('oc_ocfilter_option_value');
			$insertOptionValue->setValues(array (
				'value_id' => $value_id,
				'option_id' => $optionId,
				'keyword' => '',
				'color' => '',
				'image' => '',
				'sort_order' => 0
			));

			$insertOptionValue->do();
		}else{
			$getOptionValue = $this->orm()->select();
			$getOptionValue->setTable('oc_ocfilter_option_value_description');
			$getOptionValue->setOutputValues('value_id');
			$getOptionValue->setConditions(array(
				'option_id' => $optionId,
				'name' => $valueName
			));
			$value_id = $getOptionValue->do();
		}

		/** Добавление связи Категория - атрибут */
		$insertOptionToCategory = $this->orm()->insert();
		$insertOptionToCategory->setTable('oc_ocfilter_option_to_category');
		$insertOptionToCategory->setValues(array(
			'option_id'=>$optionId,
			'category_id'=>$category
		));
		$insertOptionToCategory->do();

		/** Добавление связи Товар - атрибут */
		$insertOptionToProduct = $this->orm()->insert();
		$insertOptionToProduct->setTable('oc_ocfilter_option_value_to_product');
		$insertOptionToProduct->setValues(array(
			'product_id'=>$id,
			'value_id'=>$value_id,
			'option_id'=>$optionId,
			'slide_value_min'=>'0.0000',
			'slide_value_max'=>'0.0000'
		));
		$insertOptionToProduct->do();
	}

	/** Устанавливает связь атрибуты - фильтр
	 * @param array $value массив значений атрибута (id, name)
	 */
	protected function setAtribute(array $value){
		$insertOptionDescription = $this->orm()->insert();
		$insertOptionDescription->setTable('oc_ocfilter_option_description');
		$insertOptionDescription->setValues(array(
				'option_id' => $value[ 'id' ],
				'language_id' => 1,
				'name' => $value[ 'name' ],
				'postfix' => '',
				'description' => '' )
		);
		$insertOptionDescription->do();

		$insertOption = $this->orm()->insert();
		$insertOption->setTable('oc_ocfilter_option');
		$insertOption->setValues(array(
			'option_id' => $value[ 'id' ],
			'type' => 'checkbox',
			'keyword' => '',
			'selectbox' => 0,
			'grouping' => 0,
			'color' => 0,
			'image' => 0,
			'status' => 1,
			'sort_order' => $value[ 'id' ] . '00'
		));
		$insertOption->do();

		$insertOptionToStore = $this->orm()->insert();
		$insertOptionToStore->setTable('oc_ocfilter_option_to_store');
		$insertOptionToStore->setValues(array (
			'option_id' => $value[ 'id' ],
			'store_id' => 0
		));
		$insertOptionToStore->do();
	}
}
