<?php

/**
 * Created by PhpStorm.
 * User: Валера
 * Date: 22.07.2016
 * Time: 23:31
 */
class products_collection extends system_base{
	/**
	 * @var products_collection $_instance
	 */
	private static $_instance = null;

	private function __construct() {
	}

	public function getProduct(int $productId){
		return new products_product($productId);
	}

	public function create(templates_product $product, templates_product_description $description){
		if(!$product->date_available){
			$product->date_available = date('Y-m-d H:i:s');
		}

		if(!$product->date_added){
			$product->date_added = date('Y-m-d');
		}

		if(!$product->date_modified){
			$product->date_modified = date('Y-m-d H:i:s');
		}
		echo 'xxxx';
		$preparedQuery = $product->prepareForQuery();
		print_r($this->db()->query("INSERT INTO `oc_product` (" . $preparedQuery['keys'] . ") VALUES (" . $preparedQuery['values'] . ")"));
		print_r("INSERT INTO `oc_product` (" . $preparedQuery['keys'] . ") VALUES (" . $preparedQuery['values'] . ")");

		$description->product_id = system_database::getInstance()->insert_id;
		$preparedQuery = $description->prepareForQuery();

		print_r($this->db()->query("INSERT INTO `oc_product_description` (" . $preparedQuery['keys'] . ") VALUES (" . $preparedQuery['values'] . ")"));

	}

	/**
	 * @return products_collection
	 */
	static public function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}