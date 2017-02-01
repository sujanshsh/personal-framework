<?php

class Email
{
    public $to;
    public $subject;
    public $headers;
    public $message;
    public $additional_headers;

    public function __construct($to,$subject)
    {
        $this->to = $to;
        $this->subject = $subject;
        include('../config/Email.php');
        $this->headers = $config_email['headers'];
        $this->additional_headers = $config_email['additional_headers'];
    }

    public function setMessage($msg) {
        $this->message = $msg;
    }

    public function sendEmail() {
        return mail($this->to,$this->subject,$this->message,$this->headers,$this->additional_headers);
    }

    public function loadMessageFromTemplate($filename,$values) {
        foreach($values as $k => $v) {
            $$k = $v;
        }
        if(is_readable($filename))
            $this->message = include $filename;
        else
            $this->message = '';
    }
}