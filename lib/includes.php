<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include('dblib.php');
include('wordlib.php');
include('clearbase.php');
include(ROOT.'/functions.php');
include(ROOT.'/modules/imageClass.php');
include(ROOT.'/modules/PriceAndAvailable.php');
include('atributes.php');
include(ROOT.'/modules/ocFilter.php');
include(ROOT.'/modules/titleGenerator.php');
include(ROOT.'/modules/productReminder.php');
$file = 'http://img.sex-opt.ru/catalogue/db_export/?type=csv&columns_separator=%3B&text_separator=%22';
//$file = 'prise.csv';

$i = 0;
$hundreedTimer = -1;
$extractDatas = '';
$iteration = 0;
$products_count = 0;
$addedProducts = array();




