<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function clearTables () {
	QueryExec("TRUNCATE oc_product_to_category");
	QueryExec("TRUNCATE oc_product_to_store");
	QueryExec("TRUNCATE oc_product");
	QueryExec("TRUNCATE oc_product_description");
	QueryExec("TRUNCATE oc_category_path");
	QueryExec("TRUNCATE oc_category");
	QueryExec("TRUNCATE oc_category_description");
	QueryExec("TRUNCATE oc_category_to_store");
//	QueryExec("TRUNCATE oc_parcer_category");
	QueryExec("TRUNCATE oc_url_alias");
	QueryExec("TRUNCATE oc_manufacturer");
	QueryExec("TRUNCATE oc_manufacturer_description");
	QueryExec("TRUNCATE oc_manufacturer_to_store");
	QueryExec("TRUNCATE oc_parcer_manufacturer");
	QueryExec("TRUNCATE oc_attribute_group");
	QueryExec("TRUNCATE oc_attribute_group_description");
	QueryExec("TRUNCATE oc_attribute");
	QueryExec("TRUNCATE oc_attribute_description");
	QueryExec("TRUNCATE oc_product_attribute");
	QueryExec("TRUNCATE oc_product_image");
	QueryExec("TRUNCATE oc_ocfilter_option");
	QueryExec("TRUNCATE oc_ocfilter_option_description");
	QueryExec("TRUNCATE oc_ocfilter_option_to_store");
	QueryExec("TRUNCATE oc_ocfilter_option_value_description");
	QueryExec("TRUNCATE oc_ocfilter_option_value");
	QueryExec("TRUNCATE oc_ocfilter_option_to_category");
	QueryExec("TRUNCATE oc_ocfilter_option_value_to_product");
	echo "Таблицы очищены!\n";
}