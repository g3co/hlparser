<?php

/*
 * Open Source/ GPL 2 license.
 */

/**
 * Description of productReminder
 *
 * @author Kabisov Valeriy
 */
class productReminder extends system_base {
    function check(){

        $sql = <<<SQL
SELECT PR.*,PD.name FROM `oc_product_remind` PR 
LEFT JOIN `oc_product` P ON P.product_id = PR.product_id 
LEFT JOIN `oc_product_description` PD ON PD.product_id = PR.product_id 
WHERE P.quantity > 0 
ORDER BY PR.`user_email` ASC
SQL;

        $remindProducts = $this->db()->query($sql)->fetch_all(MYSQLI_ASSOC);
        if(empty($remindProducts)) return [];

        $output = [];
        $lastEmail = '';
        $site = config::SITE_URL;

        foreach($remindProducts as $remindItem){
            $remindItem['link'] = $site . '/index.php?route=product/product&product_id=' . $remindItem['product_id'];
            if($lastEmail != $remindItem['user_email']){
                $output[] = ['products'=>[$remindItem],'email'=>$remindItem['user_email']];
                $lastEmail = $remindItem['user_email'];
            }else{
                $output[count($output) - 1]['products'][] = $remindItem;
            }
            $this->orm()->delete()->setTable('oc_product_remind')->setConditions(['id'=>$remindItem['id']])->do();
        }

        return $output;
    }
}
