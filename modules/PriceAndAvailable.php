<?php

class PriceAndAvailable {
    
    public $categories = NULL;
    
    protected function getCategoryInfo($category){
        if(!$this->categories || !isset($this->categories[$category])){
            foreach(QueryGet("SELECT * FROM oc_parcer_category") as $value){
                $this->categories[$value['id']]=$value;
            }
        }
        return $this->categories[$category];
    }

    public function writePA($input){
        $price = $input['price'] + $input['price']*$input['categoryArr']['margin']/100;
        if((int)$input['price'] <= 100) {$price = $price * 3;}
		elseif((int)$input['price'] > 100 && (int)$input['price'] <= 200) {$price = $price * 2.3;}
		elseif((int)$input['price'] > 200 && (int)$input['price'] <= 500) {$price = $price * 1.8;}
		elseif((int)$input['price'] > 500 && (int)$input['price'] <= 1000) {$price = $price * 1.3;}
		elseif((int)$input['price'] > 1000 && (int)$input['price'] <= 1500) {$price = $price * 1.1;}
		elseif((int)$input['price'] > 1500 && (int)$input['price'] <= 2000) {$price = $price * 1;}
		elseif((int)$input['price'] > 2000 && (int)$input['price'] <= 3000) {$price = $price * 0.9;}
		elseif((int)$input['price'] > 3000 && (int)$input['price'] <= 4000) {$price = $price * 0.85;}
		elseif((int)$input['price'] > 4000 && (int)$input['price'] <= 5000) {$price = $price * 0.82;}
		elseif((int)$input['price'] > 5000 && (int)$input['price'] <= 7000) {$price = $price * 0.8;}
		elseif((int)$input['price'] > 7000 && (int)$input['price'] <= 9000) {$price = $price * 0.75;}
		elseif((int)$input['price'] > 9000) {$price = $price * 0.7;}

		$price = round($price,-1);

        $quantity = ($input['available']) ? $input['categoryArr']['quantity'] : 0;

		$sku = (100000 - $input['productId'])*16;
		
        QueryExec("UPDATE oc_product SET `quantity` = '".$quantity."', "
                . "`price` = '".$price."', mpn = '".$sku."' WHERE product_id = ".$input['productId']);
    }
}
