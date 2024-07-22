<?php

namespace App\Services\Whatsapp;

class Whatsapp{

    protected string $message;
    protected string $template;
    protected array $params;
    protected string $to;

    public function __construct(public string $token, public string $phone_number_id)
    {
        
    }

    public function message(string $message){
        $this->message = $message;
        return $this;
    }

    public function template(string $template, array $params = []){
        $this->template = $template;
        $this->params = $params;
        return $this;
    }

    public function to(string $to){
        $this->to = $to;
        return $this;
    }

    public function send(){

        $url = "https://graph.facebook.com/v18.0/".$this->phone_number_id."/messages";

        $ch = curl_init();
        $ret = curl_setopt($ch, CURLOPT_URL,$url);

        $header = [];
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Bearer '.$this->token;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $this->to,
            'type' => 'template',
            'template' => [
                'name' => $this->template,
                'language' => [
                    'code' => 'en_US'
                ]
            ]
        ];
        curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curlresponse = curl_exec($ch);
        curl_close($ch);

        return true;
    }
    
}