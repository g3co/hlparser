<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ocFilter
 *
 * @author PHP-1
 */
class ocFilter {
    private $atributes = array();
    public $values = array();
    public $counter = 0;
    protected $first = NULL;
    public $rusize = NULL;
    
    function __construct($atributes){
        
        $this->rusize['name'] = 'Российский размер';
    }
    
    public function checkFirst(){
        if(count(QueryGet("SELECT * FROM oc_ocfilter_option_to_category"))){
            $this->first = false;
        }else{
            $this->first = true;
        }
    }
    
    public function set($value,$id,$category){
        $valueName = mb_strtolower($value['value'], "utf-8");

        if(QueryExec("INSERT INTO oc_ocfilter_option_description "
                    . "(`option_id`, `language_id`, `name`, `postfix`, `description`) "
                    . "VALUES ('{$value['id']}', '1', '{$value['name']}', '', '')",$debug=true,$debugLevel=0)){
                        
                        $AI = QueryGet("SHOW TABLE STATUS LIKE 'oc_ocfilter_option_description'");
                        
                        QueryExec("INSERT INTO oc_ocfilter_option "
                                . "(`option_id`, `type`, `keyword`, `selectbox`, `grouping`,"
                                . " `color`, `image`, `status`, `sort_order`) "
                                . "VALUES ('".$AI[0]['Rows']."', 'checkbox', "
                                . "'', '0', '0', '0', '0', '1', '".$AI[0]['Rows']."00')");

                        QueryExec("INSERT INTO oc_ocfilter_option_to_store "
                                . "(`option_id`, `store_id`) VALUES ('".$AI[0]['Rows']."', '0')");     
                    }
                    
        if(!isset($this->rusize['option_id'])){
            $rusize = QueryGet("SELECT * FROM `oc_ocfilter_option_description` "
                             . "WHERE `name` = '".$this->rusize['name']."'");
   
            if(isset($rusize[0]['option_id'])){
                $this->rusize['option_id'] = $rusize[0]['option_id'];
            }           
        }
        
        if(isset($this->rusize['option_id']) && $value['id'] == $this->rusize['option_id']){
            $rusizeExplode = explode('-',$valueName);
       
            if(count($rusizeExplode) > 1){
                $start = $rusizeExplode[0];
                $stop = $rusizeExplode[1];
                $rusizeExplode = array();
                for($i=2;$start <= $stop;$start += $i){
                    $rusizeExplode[] = $start;
                }
            }
            foreach($rusizeExplode as $valueName){
                if(QueryExec("INSERT INTO `honeylovers`.`oc_ocfilter_option_value_description` "
                            . "(`value_id`, `option_id`, `language_id`, `name`) "
                            . "VALUES ('', '{$value['id']}', '1', '{$valueName}')",$debug=true,$debugLevel=0)){
                        $AI = QueryGet("SHOW TABLE STATUS LIKE 'oc_ocfilter_option_value_description'");
                        QueryExec("INSERT INTO `honeylovers`.`oc_ocfilter_option_value` "
                            . "(`value_id`, `option_id`, `keyword`, `color`, `image`, `sort_order`)"
                            . " VALUES ('{$AI[0]['Rows']}', '{$value['id']}', '', '', '', '0')"); 
                }

                $value_id = QueryGet("SELECT value_id FROM `oc_ocfilter_option_value_description` "
                        . "WHERE `name` LIKE '".$valueName."'");

                if(isset($value_id[0])){
                    if($this->first){
                        QueryExec("INSERT INTO `oc_ocfilter_option_to_category`
                        (`option_id`, `category_id`) VALUES ('".$value['id']."', '".$category."')");

                        QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` "
                            . "(`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) "
                            . "VALUES ('".$id."', '".$value_id[0]['value_id']."', "
                            . "'".$value['id']."', '0.0000', '0.0000')");
                    }else{
                        $atribInCat = QueryGet("SELECT * FROM oc_ocfilter_option_to_category "
                            . "WHERE category_id='".$category."' AND option_id='".$value['id']."'");
                        if(count($atribInCat)){
                            QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` "
                                . "(`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) "
                                . "VALUES ('".$id."', '".$value_id[0]['value_id']."', "
                                . "'".$value['id']."', '0.0000', '0.0000')");
                        }
                    }
                }
            }
        

        }else{
            if(QueryExec("INSERT INTO `honeylovers`.`oc_ocfilter_option_value_description` "
                        . "(`value_id`, `option_id`, `language_id`, `name`) "
                        . "VALUES ('', '{$value['id']}', '1', '{$valueName}')",$debug=true,$debugLevel=0)){
                    $AI = QueryGet("SHOW TABLE STATUS LIKE 'oc_ocfilter_option_value_description'");
                    QueryExec("INSERT INTO `honeylovers`.`oc_ocfilter_option_value` "
                        . "(`value_id`, `option_id`, `keyword`, `color`, `image`, `sort_order`)"
                        . " VALUES ('{$AI[0]['Rows']}', '{$value['id']}', '', '', '', '0')"); 
            }

            $value_id = QueryGet("SELECT value_id FROM `oc_ocfilter_option_value_description` "
                    . "WHERE `name` LIKE '".$valueName."'");

			if(!empty($value_id[0])){
				if($this->first){
					QueryExec("INSERT INTO `oc_ocfilter_option_to_category` 
						(`option_id`, `category_id`) VALUES ('".$value['id']."', '".$category."')");

					QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` "
							. "(`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) "
							. "VALUES ('".$id."', '".$value_id[0]['value_id']."', "
							. "'".$value['id']."', '0.0000', '0.0000')");
				}else{
					$atribInCat = QueryGet("SELECT * FROM oc_ocfilter_option_to_category "
							 . "WHERE category_id='".$category."' AND option_id='".$value['id']."'");
					if(count($atribInCat)){
						QueryExec("INSERT INTO `oc_ocfilter_option_value_to_product` "
							. "(`product_id`, `value_id`, `option_id`, `slide_value_min`, `slide_value_max`) "
							. "VALUES ('".$id."', '".$value_id[0]['value_id']."', "
								. "'".$value['id']."', '0.0000', '0.0000')");
					}
				} 
			}
        }
    }
        
    
}
