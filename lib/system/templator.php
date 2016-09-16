<?php

/**
 * Created by PhpStorm.
 * User: valera
 * Date: 15.09.16
 * Time: 22:20
 */
class system_templator
{
    /**
     * @var array $_instance
     */
    private static $_instance = [];
    protected $templateName = '';

    public function generate($vars = []){
        extract($vars);
        ob_start();
        include ROOT . '/templates/' . $this->templateName . ".php";
        return ob_get_clean ();
    }

    public function setName($templateName){
        $this->templateName = $templateName;
    }

    /**
     * @param $templateName
     * @return system_templator
     */
    static public function loadTemplate($templateName) {
        if (!isset(self::$_instance[$templateName])) {
            $templateEngine = new self();
            $templateEngine->setName($templateName);
            self::$_instance[$templateName] = $templateEngine;
        }
        return self::$_instance[$templateName];
    }
}