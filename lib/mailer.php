<?php

class mailer {
    private static $_instance = null;
    protected $mail = null;

    private function __construct()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = config::SMTP_SERVER;
        $mail->SMTPAuth = true;
        $mail->Username = config::SMTP_USER;
        $mail->Password = config::SMTP_PASS;
        $mail->SMTPSecure = config::SMTP_PROTOCOL;
        $mail->Port = config::SMTP_PORT;
        $mail->CharSet = 'utf8';

        $mail->setFrom(config::SMTP_USER, config::SITE_NAME);
        $mail->isHTML(true);
        $this->mail = $mail;
    }

    public function setAddress($email){
        $this->mail->clearAddresses();
        $this->mail->addAddress($email);
        return $this;
    }

    public function setSubject($subject){
        $this->mail->Subject = $subject;
        return $this;
    }

    public function setContent($content){
        $this->mail->Body = $content;
        return $this;
    }

    public function send(){
        $this->mail->send();
    }

    /**
     * @return mailer
     */
    static public function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}