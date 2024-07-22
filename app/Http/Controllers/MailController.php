<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Google\Gmail;
use App\Models\CommunicationLog;
use App\Services\EmailService;

class MailController extends Controller
{
    public function fetchMails(Gmail $gmail){

        ini_set('max_execution_time', 0);
        $last_mail = CommunicationLog::orderBy('message_date', 'DESC')->first();
        if($last_mail){
            $since = new \DateTimeImmutable($last_mail->message_date);
        }
        else{
            $today = new \DateTimeImmutable();
            $since = $today->sub(new \DateInterval('PT10M'));
        }

        $mails = $gmail->fetchMails($since);
        if(count($mails)){
            $mail_service = New EmailService();
            $mail_service->storeReceivedEmails($mails);
        }
        exit;
    }
}
