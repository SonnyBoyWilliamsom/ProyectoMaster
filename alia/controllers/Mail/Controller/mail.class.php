<?php

class Mail{
    protected $from;
    protected $to;
    protected $subject;
    protected $messageHTML;
    protected $messageText;
    private $headers;
    private $sign;
    private $phpMailer;

    function __construct(){
        include_once(getRoot()."/controllers/Mail/Resources/class/PHPMailerAutoload.php");
        $this->phpMailer=new PHPMailer();
    }
    function setDestiny($mail,$name){
        $this->to=$mail;
        $this->toName=$name;
    }
    function setRemiter($mail,$name){
        $this->from=$mail;
        $this->fromName=$name;
    }
    private function getSign(){
        ob_start();
        include getRoot()."/controllers/Mail/View/html/sign.html.php";
        $this->sign=ob_get_clean();
    }
    public function setMessage($data,$subject,$template="default"){
        ob_start();
        include getRoot()."/controllers/Mail/View/html/$template.html.php";
        $this->messageHTML=ob_get_clean();
        include getRoot()."/controllers/Mail/View/text/$template.txt.php";
        $this->messageText=ob_get_clean();
        $this->subject=$subject;
        $this->getSign();
    }
    function send(){
        $this->phpMailer->CharSet = 'UTF-8';
        $this->phpMailer->Host = "mail.alcorconalia.com";
        $this->phpMailer->Username = "formularios@alcorconalia.com";
        $this->phpMailer->Password = "contactos@2015!";
        $this->phpMailer->setFrom($this->from,$this->fromName);
        $this->phpMailer->addReplyTo($this->from,$this->fromName);
        $this->phpMailer->addAddress($this->to,$this->toName);
        $this->phpMailer->Subject=$this->subject;
        $this->phpMailer->msgHTML($this->messageHTML.$this->sign);
        $this->phpMailer->AltBody = $this->messageText;
        return $this->phpMailer->send();
    }
}
