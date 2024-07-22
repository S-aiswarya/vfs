<?php

namespace App\Services\SMS;

abstract class Sms{


    public function __construct(public string $phone_numbers, public string $message)
    {
        
    }

    abstract public function sendSms();
}