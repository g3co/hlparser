<?php

/** report
 * Класс для отправки отчётов
 * @param array $recipient содержит конфигурационную информацию о получателе письма, подробности ниже
 *   email => емэйл получателя
 *   name => имя получателя
 * @param string $subject Тема письма
 */
class report extends YandexSMTP{
	public $mailer = NULL;
	public $recipient = array(); 
	public $subject = NULL;
	public $message = '';
	
	/**
	 * Зацепляем доп библотеку phpMailer для отправки по SMTP
	 * @param string $phpMailer путь до библиотеки phpMailer
	 * @param string $class класс обёртки, например YandexSmtp
	 */
	function __construct($phpMailer, $class){
		include($phpMailer);
		$this->mailer = new $class;
	}

	/**
	 * Добавляет текстовый блок к конечному отчёту
	 * @param string $content контент блока
	 * @param string $title заголовок блока
	 */
	function addBlock($content = NULL,$title = NULL){
		$mailMessage = '';
		if($title) $mailMessage .= "<h2>".$title."</h2>";
		if($content)$mailMessage .= "<p>".$content."</p>";
		$this->message .= $mailMessage;
	} 

	/**
	 * Отсылает сообщение на email указанный в настройках
	 */
	function send(){
		if(!$this->recipient) die("Не введены пользовательские данные \n");
		$this->mailer->to = $this->recipient;
		$this->mailer->subject = $this->subject;
		$this->mailer->makeMessage($this->message);
		$this->mailer->sendMail();
	}	
}