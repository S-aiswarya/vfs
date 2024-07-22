<?php

namespace App\Services;

class MailSettings{

    protected $mail_to;
    protected $mail_cc;
    protected $mail_bcc;

    public function to($mail_to){
        $this->mail_to = $mail_to;
        return $this;
    }

    public function cc($mail_cc){
        $this->mail_cc = $mail_cc;
        return $this;
    }

    public function bcc($mail_bcc){
        $this->mail_bcc = $mail_bcc;
        return $this;
    }

    public function send($mailer)
    {
        $mail = ['to'=>$this->mail_to];
        if($this->mail_cc)
            $mail['cc'] = $this->mail_cc;
        if($this->mail_bcc)
            $mail['bcc'] = $this->mail_bcc;

        dispatch(new \App\Jobs\SendEmailJob($mail, $mailer));
        
    }
}