<?php

namespace App\Services\SMS;

class Greenads extends Sms{
    
    public function sendSms()
    {
        $username ="smskcpmc";
        $password="kumili123";
        $mobilenumbers=$this->phone_numbers;
        $sender="KCPOTP";
        $username = urlencode($username); 
        $password = urlencode($password); 
        $message = urlencode($this->message); 
        $route = "T"; //your route id 
        $peid = "1701159663118578799"; //your 19-digit Entity ID 
        $tempid = "1707162996340927122"; //your 19-digit Template ID 

        //Your senderid

        $url = "http://bulksmscochin.com/sendsms";

        $ch = curl_init();
        $ret = curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,"uname=$username&pwd=$password&senderid=$sender&to=$mobilenumbers&msg=$message&route=$route&peid=$peid&tempid=$tempid");
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curlresponse = curl_exec($ch);

        curl_close($ch);

        return true;
    }
}