<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of imageClass
 *
 * @author валера
 */
class imageClass {
    public $imagePath = '/home/honeylovers.ru/image/data/sexshop/';
    public $pathToDB = 'data/sexshop/';
    
    
    function get_file($url,$file) 
    {
        $ch = curl_init();
        $fp = fopen($file, 'wb');
        curl_setopt($ch, CURLOPT_URL, $url); //url
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
    
    function getImageName($path){
		// print_r($path);
		$exploded = explode('/', $path);
        return end($exploded);
    }
    
    function setImage($imgPath){
        if($imgPath){
            $imageName = $this->getImageName($imgPath);
            if(file_exists($this->imagePath.$imageName)){
                return($this->pathToDB.$imageName);
            }else{
                $this->get_file($imgPath,$this->imagePath.$imageName);
                sleep(0.5);
                if(file_exists($this->imagePath.$imageName)){
                    echo $this->pathToDB.$imageName."\n";
                    return($this->pathToDB.$imageName);
                }else{
                    return('');
                }
            }
        }
    }
}
