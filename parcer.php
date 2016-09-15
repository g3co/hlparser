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


			$oData->manufacturer = ($oData->manufacturer != "Презервативы") ? $oData->manufacturer : $oData->manufacturer . " производитель";
			insertManufacturer($oData->manufacturer);

			/**	Список товаров
			 */
			$keywords = implode(", ", explode(" ", $wordlib->clearstr($oData->title))) . ", " . implode(', ', $categoryArr);

			$atributesFromName = $atributes->fromName($data[ 2 ]);

			include(ROOT . '/atributesRule.php');
			$title = $titleGenerator->getTitle(array(
				$wordlib->clearTitle($oData->title),
				(isset($categoryArr[ 1 ])) ? $categoryArr[ 1 ] : $categoryArr[ 0 ],
				$oData->manufacturer,
				$wordlib->translitEN($wordlib->clearTitle($oData->title)),
				$atributes->getParam('color')
			));

			/** Создание товара
			 */
			$oProductTemplate = new templates_product;
			$oProductTemplate->product_id = NULL;
			$oProductTemplate->model = $oData->code;
			$oProductTemplate->sku = $oData->code;
			$oProductTemplate->isbn = config::SEX_OPT_STORE;
			$oProductTemplate->stock_status_id = 5;
			$oProductTemplate->quantity = 1;
			$oProductTemplate->tax_class_id = 1;
			$oProductTemplate->shipping = 1;
			$oProductTemplate->image = $imageClass->setImage($oData->image);
			$oProductTemplate->manufacturer_id = getManufacturerId($oData->manufacturer);

			$oProductDescriptionTemplate = new templates_product_description;
			$oProductDescriptionTemplate->name = $oData->title;
			$oProductDescriptionTemplate->description = $oData->description;
			$oProductDescriptionTemplate->meta_keyword = $keywords;
			$oProductDescriptionTemplate->seo_title = $title;
			$oProductDescriptionTemplate->seo_h1 = $oData->title;

			$id = products_collection::getInstance()->create($oProductTemplate, $oProductDescriptionTemplate);

			/** Внесение товара в магазин
			 */
			$insertProductToStore = system_orm::getInstance()->insert();
			$insertProductToStore->setTable('oc_product_to_store');
			$insertProductToStore->setValues(array (
				'product_id' => $id,
				'store_id' => '0'
			));
			$insertProductToStore->do();

			/** Внесение товара в категорию
			 */
			$insertProductToCategory = system_orm::getInstance()->insert();
			$insertProductToCategory->setTable('oc_product_to_category');
			$insertProductToCategory->setValues(array (
				'product_id' => $id,
				'category_id' => $catId,
				'main_category' => '1'
			));
			$insertProductToCategory->do();

			/** Добавление дополнительных изображений
			*/
			foreach (array($oData->image1,$oData->image2) as $additionalImage) {
				if(empty($additionalImage)) continue;
				$insertProductImage = system_orm::getInstance()->insert();
				$insertProductImage->setTable('oc_product_image');
				$insertProductImage->setValues(array (
					'product_id' => $id,
					'image' => $imageClass->setImage($additionalImage) ,
					'sort_order' => '0'
				));
				$insertProductImage->do();
			}


			makeUrl("product_id=" . $id, $wordlib->translitRU($data[ 2 ]) . "_" . $id);


			foreach ($atributes->get() as $atributBit) {
				$insertProductAttribute = system_orm::getInstance()->insert();
				$insertProductAttribute->setTable('oc_product_attribute');
				$insertProductAttribute->setValues(array (
					'product_id' => $id,
					'attribute_id' => $atributBit['id'],
					'language_id' => '1',
					'text' => $atributBit['value']
				));
				$insertProductAttribute->do();

				$atributes->filter->set($atributBit, $id, $catId);
			}

			/** Массив для отчёта
			 */

			$products_count++;
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


/** Напоминалка о поступлении товара*/
$reminder = new productReminder();
$reminder->check();

echo system_templator::getInstance('reminder')->generate(['test'=>'test variable']);

?>

<?php if(config::CONSOLE_DEBUG):?>
Добавлено товаров <?= $products_count . "\n" ?>
Всего итераций <?= $iteration . "\n" ?>
Страница создана за <?= round(microtime(true) - $start, 3) ?> сек.
Совпадений в прайсе <?= $matchesR ?>.
<?php endif;