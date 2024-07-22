<?php

namespace App\SpiderMail;

use App\Mail\MailTemplate;
use App\Services\ActionService;

class SpiderMail
{
    protected $mail_to;
    protected $mail_cc;
    protected $mail_bcc;
    protected $template;
    protected $mailer_data = [];
    protected $mailer = null;

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

    public function template($template){
        $this->template = $template;
        return $this;
    }

    public function content($data, $type=null){
        if($this->template){
            $action = new ActionService();
            $this->mailer_data = $action->process($this->template, $type, $data);
            $this->mailer = new MailTemplate($this->mailer_data);
        }
        else{
            $this->mailer = new MailTemplate($data);
        }
        return $this;
    }

    public function send()
    {
        if($this->mailer){
            $mail = ['to'=>$this->mail_to];
            $mail['cc'] = ($this->mail_cc)?$this->mail_cc:[];
            if(!empty($this->mailer_data['default_cc']))
                $mail['cc'] = array_merge($mail['cc'], $this->mailer_data['default_cc']);
            if($this->mail_bcc)
                $mail['bcc'] = $this->mail_bcc;

            dispatch(new \App\Jobs\SendEmailJob($mail, $this->mailer));
        }
        
    }

}