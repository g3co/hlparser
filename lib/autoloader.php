<?php

function autoLoader ($class) {
    $class = mb_strtolower($class);
	if(file_exists(LIB_ROOT . $class.".php")){
		include_once LIB_ROOT . $class.".php";
	}else{
		$cutClass = explode("_",$class);
		$path = "";

		while(count($cutClass) > 1){
			$partOfClass = array_shift($cutClass);
			$path .= $partOfClass.'/';


			if(file_exists(LIB_ROOT . $path . implode('_', $cutClass) . ".php")){
				include_once LIB_ROOT . $path . implode('_', $cutClass) . ".php";
				break;
			}
		}
	}
}

spl_autoload_register('autoLoader');