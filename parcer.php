<?php
/* Версия 1.1.0
 * Парсер прайс листа sex-opt.ru
 * для honeylovers.ru
 */
$start = microtime(true);

define('ROOT', __DIR__);
define('LIB_ROOT', ROOT . '/lib/');
include_once 'config.php';
include_once 'lib/autoloader.php';

$dbConnect = system_database::getInstance();
$dbConnect->connect(config::DB_HOST, config::DB_NAME, config::DB_PASSWD, config::DB_USERNAME);

include(ROOT . '/lib/includes.php');


if (isset($argv[ 1 ]) && $argv[ 1 ] == 'new') {
	echo "Вы собирайтесь очистить таблицы \n";
	while (true) {
		echo "Введите пожалуйста подтверждение (Y/N):";
		$clear_base_confirm = trim(fgets(STDIN));
		if ($clear_base_confirm == "Y" || $clear_base_confirm == "y") {
			clearTables();
			break;
		} elseif ($clear_base_confirm == "N" || $clear_base_confirm == "n") {
			echo "Таблицы не очищены \n";
			break;
		} else {
			echo "Неверный ключ \n";
		}
	}
}

$imageClass = new imageClass;
$imageClass->imagePath = ROOT . '/../image/data/sexshop/';
$imageClass->pathToDB = 'data/sexshop/';

$paa = new PriceAndAvailable;
$titleGenerator = new titleGenerator(ROOT . "/cfg/title.xml");
$atributes = new atributes($atributesArr, $wordlib->get_xml(ROOT . "/atribut.xml"));

$oData = new templates_csv_data();
if (($handle = fopen($file, "r")) !== FALSE) {
	// Часть первая формируем массив из строковых категорий

	foreach (QueryGet("SELECT * FROM oc_parcer_category") as $category) {
		$categoryArrBase[ $category[ 'id' ] ] = $category;

	}


	while (($data = fgetcsv($handle, 100000000, ";")) !== FALSE) {

		$id = NULL;
		$catId = NULL;
		$iteration++;
		$catArr = array();

		/** Первая строчка, собираем названия колонок */
		if($iteration === 1){
			$arrDataNames = $data;
			continue;
		}

		$arrNamedData = array_combine ($arrDataNames, $data);
		$oData->update($arrNamedData);



		$request = QueryGet("SELECT P.product_id,P.sku,PC.category_id FROM `oc_product` P LEFT JOIN oc_product_to_category PC ON P.product_id = PC.product_id WHERE P.sku = '" . $data[ 0 ] . "' AND PC.main_category = 1");


		if (empty($request) && $data[ 34 ]) {
			/* 	Создаём список категорий
			 */
			$products_count++;
			$categoryArr = explode('/', $data[ 6 ]);

			if (isset($categoryArr[ 1 ])) {
				insertCat('NULL', $categoryArr[ 0 ], $data[ 6 ], 0);
				insertCat('NULL', $categoryArr[ 1 ], $data[ 6 ], getId($categoryArr[ 0 ]));
				$catArr = getCatArr($data[ 6 ], true);
				$catId = $catArr[ 'target_id' ];
			} else {
				insertCat('NULL', $categoryArr[ 0 ], $data[ 6 ], 0);
				$catArr = getCatArr($data[ 6 ], false);
				$catId = $catArr[ 'target_id' ];
			}

			if($catArr['target_id'] == 0) {
				continue;
			}

			$data[ 4 ] = ($data[ 4 ] != "Презервативы") ? $data[ 4 ] : $data[ 4 ] . " производитель";
			insertManufacturer($data[ 4 ]);

			/* 	Список товаров
			 */
			$keywords = implode(", ", explode(" ", $wordlib->clearstr($oData->title))) . ", " . implode(', ', $categoryArr);

			$atributesFromName = $atributes->fromName($data[ 2 ]);

			include(ROOT . '/atributesRule.php');

			$title = $titleGenerator->getTitle(array(
				$wordlib->clearTitle($oData->title),
				(isset($categoryArr[ 1 ])) ? $categoryArr[ 1 ] : $categoryArr[ 0 ],
				$data[ 4 ],
				$wordlib->translitEN($wordlib->clearTitle($oData->title)),
				$atributes->getParam('color')
			));

			$oProductTemplate = new templates_product;
			$oProductTemplate->product_id = NULL;
			$oProductTemplate->model = $oData->code;
			$oProductTemplate->sku = $oData->code;
			$oProductTemplate->isbn = config::SEX_OPT_STORE;
			$oProductTemplate->stock_status_id = 5;
			$oProductTemplate->quantity = 1;
			$oProductTemplate->tax_class_id = 1;
			$oProductTemplate->shipping = 1;
			$oProductTemplate->image = $imageClass->setImage($data[ 13 ]);
			$oProductTemplate->manufacturer_id = getManufacturerId($data[ 4 ]);

			$oProductDescriptionTemplate = new templates_product_description;
			$oProductDescriptionTemplate->name = $oData->title;
			$oProductDescriptionTemplate->description = $oData->description;
			$oProductDescriptionTemplate->meta_keyword = $keywords;
			$oProductDescriptionTemplate->seo_title = $title;
			$oProductDescriptionTemplate->seo_h1 = $oData->title;

			$id = products_collection::getInstance()->create($oProductTemplate, $oProductDescriptionTemplate);

			$sql = "INSERT INTO oc_product_to_store (`product_id` ,`store_id`) VALUES ({$id}, '0')";
			QueryExec($sql, true);

			$sql = "INSERT INTO oc_product_to_category (`product_id`, `category_id`, `main_category`) " . "VALUES ('{$id}', '{$catId}', '1')";
			QueryExec($sql, true);


			if ($data[ 14 ]) {
				QueryExec("INSERT INTO `oc_product_image` (`product_id`, `image`, `sort_order`) " . "VALUES ('{$id}', '" . $imageClass->setImage($data[ 14 ]) . "', '0');", true);
			}
			if ($data[ 15 ]) {
				QueryExec("INSERT INTO `oc_product_image` (`product_id`, `image`, `sort_order`) " . "VALUES ('{$id}', '" . $imageClass->setImage($data[ 15 ]) . "', '0');", true);
			}


			makeUrl("product_id=" . $id, $wordlib->translitRU($data[ 2 ]) . "_" . $id);

			foreach ($atributes->get() as $atributBit) {

				QueryExec("INSERT INTO `oc_product_attribute` " . "(`product_id`, `attribute_id`, `language_id`, `text`) " . "VALUES ('{$id}', '{$atributBit['id']}', '1', '{$atributBit['value']}')", true);


				$atributes->filter->set($atributBit, $id, $catId);
			}

			/* Массив для отчёта
			 */
			$addedProducts[] = array( 'productId' => $id, 'image' => $imageClass->setImage($data[ 13 ]), 'name' => $oData->title );
		}else if(!empty($request)) {
			$requestItem = current($request);
			$id = !empty($requestItem['product_id']) ? $requestItem['product_id'] : false;
			$catArr = !empty($requestItem['category_id']) ?  $categoryArrBase[$requestItem['category_id']] : false;
		}

		if ($id && !empty($catArr)) {
			$paa->writePA(array( 'productId' => $id, 'categoryArr' => $catArr, 'price' => $data[ 11 ], 'available' => $data[ 34 ] ));
		}

		/** Вывод в консоль информации о каждой 1000 обработанных товаров */
		if (config::CONSOLE_DEBUG && (floatval($iteration/1000) == intval($iteration/1000))) echo $iteration . "\n";
	}
}

foreach (QueryGet("SELECT * FROM oc_parcer_category WHERE `target_id` != '0'") as $categoryBit) {
	if (!QueryGet("SELECT category_id FROM oc_category WHERE category_id = '" . $categoryBit[ 'id' ] . "'")) {
		makeCategory($categoryBit[ 'id' ], $categoryBit[ 'parent' ], $categoryBit[ 'name' ], $categoryBit[ 'target_id' ]);
		makeUrl("category_id=" . $categoryBit[ 'id' ], $wordlib->translitRU($categoryBit[ 'name' ]));
	}
}

foreach (QueryGet("SELECT * FROM oc_parcer_manufacturer") as $manufacturerBit) {
	if (!QueryGet("SELECT manufacturer_id FROM oc_manufacturer WHERE manufacturer_id = '" . $manufacturerBit[ 'manufacturer_id' ] . "'")) {
		makeManufacturer($manufacturerBit[ 'manufacturer_id' ], $manufacturerBit[ 'group' ]);
		makeUrl("manufacturer_id=" . $manufacturerBit[ 'manufacturer_id' ], $wordlib->translitRU($manufacturerBit[ 'group' ]));
	}
}
?>

<?php if(config::CONSOLE_DEBUG):?>
Добавлено товаров <?= $products_count . "\n" ?>
Всего итераций <?= $iteration . "\n" ?>
Страница создана за <?= round(microtime(true) - $start, 3) ?> сек.
Совпадений в прайсе <?= $matchesR ?>.
<?php endif;