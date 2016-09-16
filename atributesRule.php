<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if ($data[ 16 ]) {
	$attributes->set('material', $data[ 16 ]);
} elseif (isset($atributesFromName->material)) {
	$attributes->set('material', $atributesFromName->material);
}
if ($data[ 17 ]) {
	$attributes->set('size', $data[ 17 ]);
} elseif (isset($atributesFromName->size)) {
	$attributes->set('size', $atributesFromName->size);
}
if ($data[ 18 ]) {
	$attributes->set('length', $data[ 18 ]);
} elseif (isset($atributesFromName->length)) {
	$attributes->set('length', $atributesFromName->length);
}
if ($data[ 19 ]) {
	$attributes->set('width', $data[ 19 ]);
} elseif (isset($atributesFromName->width)) {
	$attributes->set('width', $atributesFromName->width);
}

if ($data[ 20 ]) {
	$attributes->set('color', $data[ 20 ]);
} elseif (isset($atributesFromName->color)) {
	$attributes->set('color', $atributesFromName->color);
} elseif (isset($remotePrice[ 0 ]) && $remotePrice[ 0 ][ 9 ]) {
	$attributes->set('color', $remotePrice[ 0 ][ 9 ]);
}

if (isset($remotePrice[ 0 ]) && $remotePrice[ 0 ][ 10 ]) {
	preg_match_all("/([0-9]+)[\s]{0,1}мл/", $remotePrice[ 0 ][ 10 ], $mods);
	if (isset($mods[ 1 ][ 0 ])) {
		$attributes->set('volume', $mods[ 1 ][ 0 ]);
	}
}

preg_match_all("/([0-9]+)[\s]{0,1}мл/", $oData->title, $mods);
if (isset($mods[ 1 ][ 0 ])) {
	$attributes->set('volume', $mods[ 1 ][ 0 ]);
}

preg_match_all("/([0-9]+)[\s]{0,1}ml/", $oData->title, $mods);
if (isset($mods[ 1 ][ 0 ])) {
	$attributes->set('volume', $mods[ 1 ][ 0 ]);
}

preg_match_all("/([0-9]+)[\s]{0,1}г/", $oData->title, $mods);
if (isset($mods[ 1 ][ 0 ])) {
	$attributes->set('volume', $mods[ 1 ][ 0 ]);
}

if ($data[ 22 ]) {
	$attributes->set('battery', $data[ 22 ]);
} elseif (isset($atributesFromName->battery)) {
	$attributes->set('battery', $atributesFromName->battery);
}
if ($data[ 23 ]) {
	$attributes->set('waterproof', ($data[ 23 ]) ? "Да" : NULL);
} elseif (isset($atributesFromName->waterproof)) {
	$attributes->set('waterproof', $atributesFromName->waterproof);
}
if ($data[ 24 ]) {
	$attributes->set('contry', $data[ 24 ]);
} elseif (isset($atributesFromName->contry)) {
	$attributes->set('contry', $atributesFromName->contry);
}
if (isset($categoryArr[ 1 ]) && $categoryArr[ 1 ] == "С вибрацией") {
	$attributes->set('vibration', "Да");
}
elseif(isset($categoryArr[1]) && $categoryArr[1] == "Без вибрации") {$attributes->set('vibration',"Нет");}

if ($categoryArr[ 0 ] == "Вибраторы") {
	$attributes->set('vibration', "Да");
}
elseif($categoryArr[0] == "Фаллоимитаторы") {$attributes->set('vibration',"Нет");}

if (isset($categoryArr[ 1 ])) {
	switch ($categoryArr[ 1 ]) {
		case 'Лав Клон':
			$attributes->set('type', "Лав Клон");
			break;
		case 'Реалистик':
			$attributes->set('type', "Реалистик");
			break;
		case 'Двойные':
			$attributes->set('type', "Двойные");
			break;
		case 'Простые':
			$attributes->set('type', "Простые");
			break;
		case 'Для точки G':
			$attributes->set('type', "Для точки G");
			break;
		case 'Многопрограммные':
			$attributes->set('type', "Многопрограммные");
			break;
	}
}

if ($categoryArr[ 0 ] == "Виброяйца") {
	$attributes->set('vibration', "Да");
	$attributes->set('type', "Виброяйца");
}

if ($categoryArr[ 0 ] == "Бабочки") {
	$attributes->set('vibration', "Да");
	$attributes->set('type', "Бабочки");
}

if ($categoryArr[ 0 ] == "Вибронаборы") {
	$attributes->set('vibration', "Да");
	$attributes->set('type', "Вибронаборы");
}

if ($categoryArr[ 0 ] == "Вибростимуляторы") {
	$attributes->set('vibration', "Да");
	$attributes->set('type', "Хай-тек");
}

if ($categoryArr[ 0 ] == "Страпоны") {
	$attributes->set('type', "Страпоны");
}

if (isset($categoryArr[ 1 ]) && $categoryArr[ 1 ] == "Сапоги") {
	$attributes->set('type', "Сапоги");
} elseif (isset($categoryArr[ 1 ]) && $categoryArr[ 1 ] == "Туфли") {
	$attributes->set('type', "Туфли");
}


switch ($data[ 6 ]) {

    /** Крема и спреи */
	case 'Духи/С феромонами мужские':
		$attributes->set('type', "Мужские");
		break;
	case 'Духи/С феромонами женские':
		$attributes->set('type', "Женские");
		break;
	case 'Духи/С феромонами для мужчин и женщин':
		$attributes->set('type', "Унисекс");
		break;
	case 'Дезодоранты интимные':
		$attributes->set('type', "Дезодоранты");
		break;

    /** Крема и спреи */
    case 'Крема и спреи/Продлевающие':
        $attributes->set('type', "Продлевающие");
        break;
    case 'Крема и спреи/Возбуждающие':
        $attributes->set('type', "Возбуждающие");
        break;

    /** Смазки */
    case 'Лубриканты/Анальные':
        $attributes->set('type', "Анальные");
        break;
    case 'Лубриканты/Вагинальные':
        $attributes->set('type', "Вагинальные");
        break;
    case 'Лубриканты/Вкусовые':
        $attributes->set('type', "Вкусовые");
        break;
    case 'Лубриканты/Возбуждающие':
        $attributes->set('type', "Возбуждающие");
        break;
    case 'Лубриканты/Продлевающие':
        $attributes->set('type', "Продлевающие");
        break;
}


/* Костыль, для обработки косячных категорий феромонов
 * 
 */
if ($categoryArr[ 0 ] == "Духи") {
	if (substr_count($oData->title, 'муж')) {
		$attributes->set('type', "Мужские");
	}
	if (substr_count($oData->title, 'жен')) {
		$attributes->set('type', "Женские");
	}
	if (substr_count($oData->title, 'Дезодор')) {
		$attributes->set('type', "Дезодоранты");
	}
}


//преобразование в российские размеры
if ($attributes->getParam('size')) {
	if ($data[ 6 ] == "Белье/Мужское белье") {
		switch (mb_strtolower($attributes->getParam('size'), "utf-8")) {
			case 'one size':
				$attributes->set('rusize', "48-56");
				break;
			case 'l':
				$attributes->set('rusize', "52-54");
				break;
			case 'm':
				$attributes->set('rusize', "48-50");
				break;
			case 'm/l':
				$attributes->set('rusize', "48-54");
				break;
			case 's':
				$attributes->set('rusize', "44-46");
				break;
			case 's/l':
				$attributes->set('rusize', "44-54");
				break;
			case 'xl':
				$attributes->set('rusize', "56-58");
				break;
			case 'xxl':
				$attributes->set('rusize', "60-62");
				break;
		}
	} else {
		switch (mb_strtolower($attributes->getParam('size'), "utf-8")) {
			case 'one size':
				$attributes->set('rusize', "42-48");
				break;
			case 's/l':
				$attributes->set('rusize', "42-46");
				break;
			case 's/m':
				$attributes->set('rusize', "42-44");
				break;
			case 'm/l':
				$attributes->set('rusize', "44-46");
				break;
			case 'l':
				$attributes->set('rusize', "46");
				break;
			case 's':
				$attributes->set('rusize', "42");
				break;
			case 'm':
				$attributes->set('rusize', "44");
				break;
			case 'xs':
				$attributes->set('rusize', "40");
				break;
			case 'xl/xxxl':
				$attributes->set('rusize', "48-52");
				break;
			case 'xl/xxl':
				$attributes->set('rusize', "48-50");
				break;
			case 'queen size':
				$attributes->set('rusize', "50-54");
				break;
			case 'l/xl':
				$attributes->set('rusize', "46-48");
				break;
			case 'xxxl':
				$attributes->set('rusize', "52");
				break;
			case 'xxl':
				$attributes->set('rusize', "50");
				break;
			case 'xl':
				$attributes->set('rusize', "48");
				break;
			case '1x/2x':
				$attributes->set('rusize', "48-54");
				break;
			case '2-m':
				$attributes->set('rusize', "42-44");
				break;
			case '3-l':
				$attributes->set('rusize', "46-48");
				break;
			case '4-xl':
				$attributes->set('rusize', "48-50");
				break;
			case '5-xxl':
				$attributes->set('rusize', "52-54");
				break;
		}
	}
}