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

		$product->product_id = NULL;

		$insert = $this->orm()->insert();
		$insert->setTable('oc_product');
		$insert->setValues($product->compileFields());

		$description->product_id = $insert->do();

		$insert = $this->orm()->insert();
		$insert->setTable('oc_product_description');
		$insert->setValues($description->compileFields());
		$insert->do();

		return $description->product_id;
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