<?php

/** Шаблон для данных полученных из csv
 * Created by PhpStorm.
 * User: Валера
 * Date: 01.08.2016
 * Time: 23:02
 */
class templates_csv_data extends system_templates {
	public $code;
	public $article;
	public $title;
	public $group_code;
	public $group_title;
	public $category_code;
	public $category_title;
	public $tmn;
	public $msk;
	public $nsk;
	public $start_price;
	public $price;
	public $discount;
	public $image;
	public $image1;
	public $image2;
	public $material;
	public $size;
	public $length;
	public $width;
	public $color;
	public $weight;
	public $battery;
	public $waterproof;
	public $country;
	public $manufacturer;
	public $barcode;
	public $new;
	public $hit;
	public $description;
	public $collection;
	public $video;
	public $url;
	public $rst;
	public $spb;
	public $fixed_price;
	public $pieces;
	public $brand_code;
	public $brand_title;
	public $created;
	public $prop_3d;
	public $width_packed;
	public $height_packed;
	public $length_packed;
	public $weight_packed;

	/** Обновляет данные в объекте
	 * @param $data масиив из csv с данными.
	 */
	function update($data){
		foreach($data as $key=>$value){
			$this->$key = addslashes($value);
		}
	}

	/** Магический метод для проверки и установки полей начинающихся на цифру (приписывается префикс prop_)
	 * @param $name string имя атрибута
	 * @param $value string значение атрибута
	 */
	function __set($name, $value) {
		$name = 'prop_' . $name;
		if(property_exists(__CLASS__, $name)){
			$this->$name = addslashes($value);
		}
	}
}