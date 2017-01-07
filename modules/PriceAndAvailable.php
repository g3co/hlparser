<?php

class PriceAndAvailable extends system_base {
    protected function priceToppingGrid(){
        return [
            100 => 2.1,
            200 => 1.8,
            500 => 1.7,
            1000 => 1.4,
            1500 => 1.1,
        ];
    }

    public function calculateProductData($input){
        $price = $input['price'] * (1 + $input['categoryArr']['margin']/100);

        foreach ($this->priceToppingGrid() as $range=>$toppingPercent){
            if($price < $range){
                $price *= $toppingPercent;
            }
        }

        $this->orm()->update()->setTable('oc_product')->setConditions(['product_id' => $input['productId']])->setValues([
            'quantity' => ($input['available']) ? $input['categoryArr']['quantity'] : 0,
            'price' => round($price, -1),
            'jan' => round($input['price'], -1),
            'mpn' => (100000 - $input['productId']) * 16
        ])->do();
    }
}
