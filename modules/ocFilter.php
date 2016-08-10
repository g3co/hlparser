<?php
/**
 * Description of ocFilter
 *
 * @author PHP-1
 */
class ocFilter {
	public $values = array();
	public $counter = 0;
	protected $first = NULL;
	protected $loadedOptions = array();

	function __construct() {
	}

	public function checkFirst() {
		if (count(QueryGet("SELECT * FROM oc_ocfilter_option_to_category"))) {
			$this->first = false;
		} else {
			$this->first = true;
		}
	}

	public function set($value, $id, $category) {
		$valueName = mb_strtolower($value[ 'value' ], "utf-8");

		/** Секция записи атрибутов
		 *  в фильтр. Только при первом прогоне
		 */
		if($this->first && !in_array($value[ 'id' ],$this->loadedOptions)){
			$this->loadedOptions[] = $value[ 'id' ];

			$insertOptionDescription = system_orm::getInstance()->insert();
			$insertOptionDescription->setTable('oc_ocfilter_option_description');
			$insertOptionDescription->setValues(array(
					'option_id' => $value[ 'id' ],
					'language_id' => 1,
					'name' => $value[ 'name' ],
					'postfix' => '',
					'description' => '' )
			);
			$insertOptionDescription->do();

			$insertOption = system_orm::getInstance()->insert();
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

			$insertOptionToStore = system_orm::getInstance()->insert();
			$insertOptionToStore->setTable('oc_ocfilter_option_to_store');
			$insertOptionToStore->setValues(array (
				'option_id' => $value[ 'id' ],
				'store_id' => 0
			));
			$insertOptionToStore->do();
		}


		if ($value[ 'id' ] == config::RUSIZE_ATRIBUTE_ID) {
			$rusizeExplode = explode('-', $valueName);

			if (count($rusizeExplode) > 1) {
				$start = $rusizeExplode[ 0 ];
				$stop = $rusizeExplode[ 1 ];
				$rusizeExplode = array();
				for ($i = 2; $start <= $stop; $start += $i) {
					$rusizeExplode[] = $start;
				}
			}
			foreach ($rusizeExplode as $valueName) {
				$insertOptionValueDescription = system_orm::getInstance()->insert();
				$insertOptionValueDescription->setTable('oc_ocfilter_option_value_description');
				$insertOptionValueDescription->setValues(array (
					'value_id'=>NULL,
					'option_id' => $value[ 'id' ],
					'language_id' => 1,
					'name' => $valueName
				));
				$value_id = $insertOptionValueDescription->do();

				if ($value_id) {

					$insertOptionValue = system_orm::getInstance()->insert();
					$insertOptionValue->setTable('oc_ocfilter_option_value');
					$insertOptionValue->setValues(array (
						'value_id' => $value_id,
						'option_id' => $value[ 'id' ],
						'keyword' => '',
						'color' => '',
						'image' => '',
						'sort_order' => 0
					));

					$insertOptionValue->do();
				}else{
					/** TODO: дописать получение $value_id если не удалось создать его */
				}

				if(true){
					QueryExec("INSERT INTO `oc_ocfilter_option_to_category`
                    (`option_id`, `category_id`) VALUES ('" . $value[ 'id' ] . "', '" . $category . "')");

					QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` " . "(`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) " . "VALUES ('" . $id . "', '" . $value_id . "', " . "'" . $value[ 'id' ] . "', '0.0000', '0.0000')");
				}
			}
		} else {
			if (QueryExec("INSERT INTO `oc_ocfilter_option_value_description` (`value_id`, `option_id`, `language_id`, `name`) VALUES (NULL, '{$value['id']}', '1', '{$valueName}')")) {
				$AI = QueryGet("SHOW TABLE STATUS LIKE 'oc_ocfilter_option_value_description'");
				QueryExec("INSERT INTO `honeylovers`.`oc_ocfilter_option_value` (`value_id`, `option_id`, `keyword`, `color`, `image`, `sort_order`) VALUES ('{$AI[0]['Rows']}', '{$value['id']}', '', '', '', '0')");
			}

			$value_id = QueryGet("SELECT value_id FROM `oc_ocfilter_option_value_description` WHERE `name` LIKE '" . $valueName . "'");

			if (!empty($value_id[ 0 ])) {
				if ($this->first) {
					QueryExec("INSERT INTO `oc_ocfilter_option_to_category` (`option_id`, `category_id`) VALUES ('" . $value[ 'id' ] . "', '" . $category . "')");

					QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` " . "(`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) " . "VALUES ('" . $id . "', '" . $value_id[ 0 ][ 'value_id' ] . "', " . "'" . $value[ 'id' ] . "', '0.0000', '0.0000')");
				} else {
					$atribInCat = QueryGet("SELECT * FROM oc_ocfilter_option_to_category WHERE category_id='" . $category . "' AND option_id='" . $value[ 'id' ] . "'");
					if (count($atribInCat)) {
						QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` (`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) " . "VALUES ('" . $id . "', '" . $value_id[ 0 ][ 'value_id' ] . "', " . "'" . $value[ 'id' ] . "', '0.0000', '0.0000')");
					}
				}
			}
		}
	}


}
