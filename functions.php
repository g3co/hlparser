<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function consoleLog($string) {
	static $consoleLogCounter = 0;
	echo "\n " . "-----------#" . (++$consoleLogCounter) . "----------- \n";
	print_r($string);
	echo "\n ------------------------- \n";
}

function makeUrl($path, $alias) {
	if (QueryGet("SELECT url_alias_id FROM oc_url_alias WHERE keyword = '" . $alias . "'")) {
		$alias .= rand(0, 1000);
	}
	QueryExec("INSERT INTO oc_url_alias (`url_alias_id`, `query`, `keyword`) VALUES (NULL, '{$path}', '{$alias}')");

}

function insertCat($id = 'NULL', $name, $next, $parent) {
	$AI = QueryGet("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE table_name='oc_parcer_category';");

	QueryExec("INSERT INTO oc_parcer_category (`id`, `target_id`, `name`,`next`,`parent`,`margin`,`quantity`) VALUES ({$id}, '" . $AI[ 0 ][ 'AUTO_INCREMENT' ] . "',  '{$name}','{$next}',{$parent},75,5)");
}

/*	Получаем Id категории по её названию из прайса
	@str	Строка из прайса с названием категории
*/
function getId($str) {
	$request = QueryGet("SELECT id FROM `oc_parcer_category` WHERE `name` = '{$str}'");
	if (isset($request[ 0 ]))
		return $request[ 0 ][ 'id' ]; else return false;
}

/*	Получаем Id категории по её полному названию из прайса
	@str	Строка из прайса с названием категории
*/
function getCat($str, $level) {
	$level = ($level) ? " AND `parent` != 0" : ''; // Костыль, выбираем подкатегорию, тк в next может находится и родительская категория
	$request = QueryGet("SELECT target_id as id FROM `oc_parcer_category` WHERE `next` = '{$str}'" . $level);
	return $request[ 0 ][ 'id' ];
}

function getCatArr($str, $level) {
	$level = ($level) ? " AND `parent` != 0" : ''; // Костыль, выбираем подкатегорию, тк в next может находится и родительская категория
	$request = QueryGet("SELECT * FROM `oc_parcer_category` WHERE `next` = '{$str}'" . $level);
    consoleLog([$str,$request,$level]);
	if (isset($request[ 0 ]))
		return $request[ 0 ]; else return false;
}

/*  Пишем названия производителей во временную базу
 *  @manufacturer название производителя из прайса
 */
function insertManufacturer($manufacturer) {
	QueryExec("INSERT INTO oc_parcer_manufacturer (`manufacturer_id`, `group`) VALUES (NULL, '{$manufacturer}')");
}

/*  Получаем id производителя из базы
 *  @manufacturer название производителя из прайса
 */
function getManufacturerId($manufacturer) {
	$request = QueryGet("SELECT manufacturer_id FROM `oc_parcer_manufacturer` WHERE `group` = '{$manufacturer}'");

	return $request[ 0 ][ 'manufacturer_id' ];
}


/*	Создание списка категорий
	@id айди 	категории
	@parent 	родительская категория
	@name 		имя категории
*/
function makeCategory($id, $parent, $name, $target) {
	/*	category
	*/
	$catOnOff = ($target == $id) ? '1' : '0';
	$top = $parent ? 0 : 1;
	$sql = "INSERT INTO oc_category (`category_id`, `image`, `parent_id`, `top`, `column`, `sort_order`, `status`, `date_added`, `date_modified`) " . "VALUES ({$id}, '', {$parent}, {$top}, '1', '1', '" . $catOnOff . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "')";

	QueryExec($sql);

	/*	category_path
	*/
	if (!$parent) {
		$sql = "INSERT INTO oc_category_path (`category_id`, `path_id`, `level`) VALUES ({$id}, {$id}, '0')";
		QueryExec($sql);
	} else {
		$sql = "INSERT INTO oc_category_path (`category_id`, `path_id`, `level`) VALUES ({$id}, {$parent}, '0')";
		QueryExec($sql);
		$sql = "INSERT INTO oc_category_path (`category_id`, `path_id`, `level`) VALUES ({$id}, {$id}, 1)";
		QueryExec($sql);
	}

	/*	category_description
	*/
	$sql = "INSERT INTO oc_category_description (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, " . "`seo_title`, `seo_h1`) VALUES ({$id}, '1', '{$name}', '', '', '', '', '')";
	QueryExec($sql);

	/*	category_to_store
	*/
	$sql = "INSERT INTO oc_category_to_store (`category_id`, `store_id`) VALUES ({$id}, '0')";
	QueryExec($sql);

}

function makeManufacturer($id, $name) {
	QueryExec("INSERT INTO oc_manufacturer (`manufacturer_id`, `name`, `image`, `sort_order`) VALUES (" . $id . ", '" . $name . "', NULL, '0')");
	QueryExec("INSERT INTO oc_manufacturer_description (`manufacturer_id`, `language_id`, `description`, `meta_description`, `meta_keyword`, `seo_title`, `seo_h1`) " . "VALUES (" . $id . ", '1', '', '', '', '', '')");
	QueryExec("INSERT INTO oc_manufacturer_to_store (`manufacturer_id`, `store_id`) VALUES (" . $id . ", '0')");
}
