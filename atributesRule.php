<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ($data[ 16 ]) {
	$atributes->set('material', $data[ 16 ]);
} elseif (isset($atributesFromName->material)) {
	$atributes->set('material', $atributesFromName->material);
}
if ($data[ 17 ]) {
	$atributes->set('size', $data[ 17 ]);
} elseif (isset($atributesFromName->size)) {
	$atributes->set('size', $atributesFromName->size);
}
if ($data[ 18 ]) {
	$atributes->set('length', $data[ 18 ]);
} elseif (isset($atributesFromName->length)) {
	$atributes->set('length', $atributesFromName->length);
}
if ($data[ 19 ]) {
	$atributes->set('width', $data[ 19 ]);
} elseif (isset($atributesFromName->width)) {
	$atributes->set('width', $atributesFromName->width);
}

if ($data[ 20 ]) {
	$atributes->set('color', $data[ 20 ]);
} elseif (isset($atributesFromName->color)) {
	$atributes->set('color', $atributesFromName->color);
} elseif (isset($remotePrice[ 0 ]) && $remotePrice[ 0 ][ 9 ]) {
	$atributes->set('color', $remotePrice[ 0 ][ 9 ]);
}

if (isset($remotePrice[ 0 ]) && $remotePrice[ 0 ][ 10 ]) {
	preg_match_all("/([0-9]+)[\s]{0,1}мл/", $remotePrice[ 0 ][ 10 ], $mods);
	if (isset($mods[ 1 ][ 0 ])) {
		$atributes->set('volume', $mods[ 1 ][ 0 ]);
	}
}

preg_match_all("/([0-9]+)[\s]{0,1}мл/", $oData->title, $mods);
if (isset($mods[ 1 ][ 0 ])) {
	$atributes->set('volume', $mods[ 1 ][ 0 ]);
}

preg_match_all("/([0-9]+)[\s]{0,1}ml/", $oData->title, $mods);
if (isset($mods[ 1 ][ 0 ])) {
	$atributes->set('volume', $mods[ 1 ][ 0 ]);
}

preg_match_all("/([0-9]+)[\s]{0,1}г/", $oData->title, $mods);
if (isset($mods[ 1 ][ 0 ])) {
	$atributes->set('volume', $mods[ 1 ][ 0 ]);
}

if ($data[ 22 ]) {
	$atributes->set('battery', $data[ 22 ]);
} elseif (isset($atributesFromName->battery)) {
	$atributes->set('battery', $atributesFromName->battery);
}
if ($data[ 23 ]) {
	$atributes->set('waterproof', ($data[ 23 ]) ? "Да" : NULL);
} elseif (isset($atributesFromName->waterproof)) {
	$atributes->set('waterproof', $atributesFromName->waterproof);
}
if ($data[ 24 ]) {
	$atributes->set('contry', $data[ 24 ]);
} elseif (isset($atributesFromName->contry)) {
	$atributes->set('contry', $atributesFromName->contry);
}
if (isset($categoryArr[ 1 ]) && $categoryArr[ 1 ] == "С вибрацией") {
	$atributes->set('vibration', "Да");
}
//elseif(isset($categoryArr[1]) && $categoryArr[1] == "Без вибрации") {$atributes->set('vibration',"Нет");}

if ($categoryArr[ 0 ] == "Вибраторы") {
	$atributes->set('vibration', "Да");
}
//elseif($categoryArr[0] == "Фаллоимитаторы") {$atributes->set('vibration',"Нет");}

if (isset($categoryArr[ 1 ])) {
	switch ($categoryArr[ 1 ]) {
		case 'Лав Клон':
			$atributes->set('type', "Лав Клон");
			break;
		case 'Реалистик':
			$atributes->set('type', "Реалистик");
			break;
		case 'Двойные':
			$atributes->set('type', "Двойные");
			break;
		case 'Простые':
			$atributes->set('type', "Простые");
			break;
		case 'Для точки G':
			$atributes->set('type', "Для точки G");
			break;
		case 'Многопрограммные':
			$atributes->set('type', "Многопрограммные");
			break;
	}
}

if ($categoryArr[ 0 ] == "Виброяйца") {
	$atributes->set('vibration', "Да");
	$atributes->set('type', "Виброяйца");
}

if ($categoryArr[ 0 ] == "Бабочки") {
	$atributes->set('vibration', "Да");
	$atributes->set('type', "Бабочки");
}

if ($categoryArr[ 0 ] == "Вибронаборы") {
	$atributes->set('vibration', "Да");
	$atributes->set('type', "Вибронаборы");
}

if ($categoryArr[ 0 ] == "Вибростимуляторы") {
	$atributes->set('vibration', "Да");
	$atributes->set('type', "Хай-тек");
}

if ($categoryArr[ 0 ] == "Страпоны") {
	$atributes->set('type', "Страпоны");
}

if (isset($categoryArr[ 1 ]) && $categoryArr[ 1 ] == "Сапоги") {
	$atributes->set('type', "Сапоги");
} elseif (isset($categoryArr[ 1 ]) && $categoryArr[ 1 ] == "Туфли") {
	$atributes->set('type', "Туфли");
}


switch ($data[ 6 ]) {
	case 'Духи/С феромонами мужские':
		$atributes->set('type', "Мужские");
		break;
	case 'Духи/С феромонами женские':
		$atributes->set('type', "Женские");
		break;
	case 'Духи/С феромонами для мужчин и женщин':
		$atributes->set('type', "Унисекс");
		break;
	case 'Дезодоранты интимные':
		$atributes->set('type', "Дезодоранты");
		break;
}

/* Костыль, для обработки косячных категорий феромонов
 * 
 */
if ($categoryArr[ 0 ] == "Духи") {
	if (substr_count($oData->title, 'муж')) {
		$atributes->set('type', "Мужские");
	}
	if (substr_count($oData->title, 'жен')) {
		$atributes->set('type', "Женские");
	}
	if (substr_count($oData->title, 'Дезодор')) {
		$atributes->set('type', "Дезодоранты");
	}
}

switch ($data[ 6 ]) {
	case 'Крема и спреи/Продлевающие':
		$atributes->set('type', "Продлевающие");
		break;
	case 'Крема и спреи/Возбуждающие':
		$atributes->set('type', "Возбуждающие");
		break;
}

switch ($data[ 6 ]) {
	case 'Лубриканты/Анальные':
		$atributes->set('type', "Анальные");
		break;
	case 'Лубриканты/Вагинальные':
		$atributes->set('type', "Вагинальные");
		break;
	case 'Лубриканты/Вкусовые':
		$atributes->set('type', "Вкусовые");
		break;
	case 'Лубриканты/Возбуждающие':
		$atributes->set('type', "Возбуждающие");
		break;
	case 'Лубриканты/Продлевающие':
		$atributes->set('type', "Продлевающие");
		break;
}
//преобразование в российские размеры
if ($atributes->getParam('size')) {
	if ($data[ 6 ] == "Белье/Мужское белье") {
		switch (mb_strtolower($atributes->getParam('size'), "utf-8")) {
			case 'one size':
				$atributes->set('rusize', "48-56");
				break;
			case 'l':
				$atributes->set('rusize', "52-54");
				break;
			case 'm':
				$atributes->set('rusize', "48-50");
				break;
			case 'm/l':
				$atributes->set('rusize', "48-54");
				break;
			case 's':
				$atributes->set('rusize', "44-46");
				break;
			case 's/l':
				$atributes->set('rusize', "44-54");
				break;
			case 'xl':
				$atributes->set('rusize', "56-58");
				break;
			case 'xxl':
				$atributes->set('rusize', "60-62");
				break;
		}
	} else {
		switch (mb_strtolower($atributes->getParam('size'), "utf-8")) {
			case 'one size':
				$atributes->set('rusize', "42-48");
				break;
			case 's/l':
				$atributes->set('rusize', "42-46");
				break;
			case 's/m':
				$atributes->set('rusize', "42-44");
				break;
			case 'm/l':
				$atributes->set('rusize', "44-46");
				break;
			case 'l':
				$atributes->set('rusize', "46");
				break;
			case 's':
				$atributes->set('rusize', "42");
				break;
			case 'm':
				$atributes->set('rusize', "44");
				break;
			case 'xs':
				$atributes->set('rusize', "40");
				break;
			case 'xl/xxxl':
				$atributes->set('rusize', "48-52");
				break;
			case 'xl/xxl':
				$atributes->set('rusize', "48-50");
				break;
			case 'queen size':
				$atributes->set('rusize', "50-54");
				break;
			case 'l/xl':
				$atributes->set('rusize', "46-48");
				break;
			case 'xxxl':
				$atributes->set('rusize', "52");
				break;
			case 'xxl':
				$atributes->set('rusize', "50");
				break;
			case 'xl':
				$atributes->set('rusize', "48");
				break;
			case '1x/2x':
				$atributes->set('rusize', "48-54");
				break;
			case '2-m':
				$atributes->set('rusize', "42-44");
				break;
			case '3-l':
				$atributes->set('rusize', "46-48");
				break;
			case '4-xl':
				$atributes->set('rusize', "48-50");
				break;
			case '5-xxl':
				$atributes->set('rusize', "52-54");
				break;
		}
	}
}