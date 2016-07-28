<?php

/**
 * Created by PhpStorm.
 * User: Валера
 * Date: 22.07.2016
 * Time: 23:07
 */
class templates_product extends system_templates {
	public $product_id;
	public $model;
	public $sku;
	public $upc = '';
	public $ean = '';
	public $jan = '';
	public $isbn = '';
	public $mpn = '';
	public $location = 0;
	public $quantity = 0;
	public $stock_status_id = 0;
	public $image = '';
	public $manufacturer_id = 0;
	public $shipping = 1;
	public $price = 0;
	public $points = 0;
	public $tax_class_id = 0;
	public $date_available = '';
	public $weight = 0;
	public $weight_class_id = 0;
	public $length = 0;
	public $width = 0;
	public $height = 0;
	public $length_class_id = 0;
	public $subtract = 1;
	public $minimum = 1;
	public $sort_order = 0;
	public $status = 1;
	public $date_added = '';
	public $date_modified = '';
	public $viewed = 0;
}