<?php
$atributesArr = array(
	"size" => 	array(
					"name" => "Размер"
				),
	"length" => 	array(
					"name" => "Длина"
				),
	"width" => 	array(
					"name" => "Диаметр"
				),
	"color" => 	array(
					"name" => "Цвет"
				),
	"contry" => 	array(
					"name" => "Страна производитель"
				),
	"waterproof" => 	array(
					"name" => "Водонепроницаемость"
				),
	"battery" => 	array(
					"name" => "Тип батареек"
				),
	"vibration" => 	array(
					"name" => "Вибрация"
				),
	"type" => 	array(
					"name" => "Тип"
				),
	"volume" => 	array(
					"name" => "Количество"
				),
	"rusize" => 	array(
					"name" => "Российский размер"
				),
	"material" => 	array(
					"name" => "Материал"
				)				
);



class atributes {
    private $atributes = array();
    private $atributesInName = array();
    private $tmpAtributes = array();
    public $filter = NULL;

    function __construct($atributes,$atributesInName){
//        die();
        QueryExec("INSERT INTO `oc_attribute_group` (`attribute_group_id`,`sort_order`) "
                . "VALUES ('1','0')");
        QueryExec("INSERT INTO `oc_attribute_group_description` (`attribute_group_id`, `language_id`, `name`)"
                . " VALUES ('1', '1', 'Основная')");
        $i = 0;
        
        foreach($atributes as $index=>$atribute){
            $i++;		
            QueryExec("INSERT INTO `oc_attribute` (`attribute_id`, `attribute_group_id`, `sort_order`) "
                    . "VALUES ('{$i}', '1', '0');");
            QueryExec("INSERT INTO `oc_attribute_description` (`attribute_id`, `language_id`, `name`) "
                    . "VALUES ('{$i}', '1', '{$atribute['name']}')");

            $atributes[$index]['id'] = $i;
        }

        $this->atributes =  $atributes;
        
        $this->filter = new ocFilter($atributes);
        $this->atributesInName =  (array)$atributesInName;
        
    }

    function set($name,$value){
        $this->tmpAtributes[$name] = $this->atributes[$name];
        $this->tmpAtributes[$name]['value']=$value;
    }

    function get(){
        $tmp = $this->tmpAtributes;
        $this->tmpAtributes = array();
        return $tmp;
    }

    function getParam($param){
        return (isset($this->tmpAtributes[$param])) ? $this->tmpAtributes[$param]['value'] : "";
    }

    function fromName($name){
        $outputArr = array();

        foreach($this->atributesInName as $atributKey => $atribut){
            foreach($atribut as $parameterKey => $parameter){
                $parameter =(array)$parameter;
                if(substr_count($name,$parameter['name'])){
                    $outputArr[$atributKey] = $parameter['value'];
                }
            }
        }
        return (object)$outputArr;
    }	
}