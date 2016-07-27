<?php

/*
 * Open Source/ GPL 2 license.
 */

/**
 * Description of productReminder
 *
 * @author Kabisov Valeriy
 */
class productReminder {
    protected $currentUser = NULL;
    protected $message = array();
    public $site = "https://honeylovers.ru";
    
    function check(){
        $remindProducts = QueryGet("SELECT PR.*,PD.name FROM `oc_product_remind` PR "
                                . "LEFT JOIN `oc_product` P ON P.product_id = PR.product_id "
                                . "LEFT JOIN `oc_product_description` PD ON PD.product_id = PR.product_id "
                                . "WHERE P.quantity > 0 "
                                . "ORDER BY PR.`user_email` ASC");
		if(count($remindProducts)){
			foreach($remindProducts as $remindProduct){
				if(!$this->currentUser){
					$this->currentUser = $remindProduct['user_email'];
				}elseif($this->currentUser != $remindProduct['user_email']){
					$messages[] = $this->makeMessage();
					$this->currentUser = $remindProduct['user_email'];
					$this->message = array();
				}
				$this->message[] = array('id'=>$remindProduct['product_id'],'name'=>$remindProduct['name']);
				QueryExec("DELETE FROM `oc_product_remind` "
						. "WHERE `product_id` = ".$remindProduct['product_id']." "
						. "AND `user_email` = '".$remindProduct['user_email']."'");
			}
			$messages[] = $this->makeMessage();
			return $messages;
        }else{
			return array();
		}
    }
    
    function makeMessage(){
        $message['email'] = $this->currentUser;
        $message['text'] = "Добрый день!<br>"
                . "Вы просили напомнить, когда некоторые товары на нашем сайте появятся в наличии.<br>";
        foreach($this->message as $productInfo){
            $message['text'] .= '<a href="'.$this->site.'/index.php?route=product/product&product_id='.
                            $productInfo['id'].'">'.$productInfo['name'].'</a><br>'; 
        }
        /*$message['text'] .= 'Теперь в наличии<br><br>'
                . 'За то, что Вам пришлось ожидать столько времени, мы дарим Вам купон на скидку'
                . '<div style="width:100%;padding:10px;background:#DBDBDB; text-align:center; margin:10px 0; font-weight:900;">XFA24SDF2</div>'
                . 'Подробности можно узнать на странице иформации <a href="#">honeylovers.ru/info</a>';*/
				
		$message['text'] .= 'Теперь в наличии<br><br>';
        
		$message['text'] = array('content'=>$message['text'],
                            'title'=>'Товары, о поступлении которых, вы просили сообщить',
                            'nav'=>'<a href="#">О Магазине</a> '
                                    . '<a href="#">Каталог товара</a> '
                                    . '<a href="#">Информация о доставке</a>',
                            'footer'=>'С уважением <br> Администрация Honeylovers.ru <br>info@honeylovers.ru');
		return $message;
    }
}
