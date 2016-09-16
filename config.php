<?php

/**
 * Created by PhpStorm.
 * User: Валера
 * Date: 22.07.2016
 * Time: 23:39
 */
class config {
    const ADMIN_EMAIL = "kabisovvaleriy@gmail.com";

    const SITE_URL = 'http://honeylovers.ru';
    const SITE_NAME= 'HoneyLovers.ru';

	const DB_HOST = 'localhost';
	const DB_USERNAME = 'honeylovers';
	const DB_PASSWD = '7mPJ6xMS3LW003Zv';
	const DB_NAME = 'honeylovers';
	const CONSOLE_DEBUG = true;

	const SEX_OPT_STORE = 0;

    /** SMTP*/
    const SMTP_SERVER = "smtp.yandex.ru";
    const SMTP_USER = 'zakaz@honeylovers.ru';
    const SMTP_PASS = '64ax4N3MQH6U2LFd';
    const SMTP_PROTOCOL = 'ssl';
    const SMTP_PORT= '465';


	#ocFilter constants

	const RUSIZE_ATRIBUTE_ID = 11; #ид атрибута - "российсикй размер"
}