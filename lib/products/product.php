<?php

/**
 * Created by PhpStorm.
 * User: Валера
 * Date: 22.07.2016
 * Time: 23:30
 *
 * @property templates_product $product
 * @property templates_product_description $product_description
 */
class products_product extends system_base{
	public $product;
	protected $product_description;

	function __construct(int $productId){
		$this->product = $this->db()->query("SELECT * FROM `oc_product` WHERE `product_id` = " . $productId)->fetch_object('templates_product');
		$this->product_description = $this->db()->query("SELECT * FROM `oc_product_description` WHERE `product_id` = " . $productId)->fetch_object('templates_product_description');
	}

	function getName(){
		return $this->description;
	}

	function save(){
		$insert = $this->orm()->insert();
		$insert->setTable('oc_product');
		$insert->setValues($this->product->compileFields());

		$insert->do();

		$insert = $this->orm()->insert();
		$insert->setTable('oc_product_description');
		$insert->setValues($this->product_description->compileFields());

		$insert->do();
	}
}