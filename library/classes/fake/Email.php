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

        $final_content = '"'.$this->subject.'"'.$this->to."\n";
        $final_content .= $this->message."\n\n";
        file_put_contents(__DIR__.'/emails.txt',$final_content,FILE_APPEND);
        return true; //mail($this->to,$this->subject,$this->message,$this->headers,$this->additional_headers);

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