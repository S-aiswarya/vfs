<?php

namespace App\Services\Google;

use Ddeboer\Imap\Server;
use Ddeboer\Imap\Search\Date\Since;

class Gmail{

    protected $connection;

    public function __construct(public string $host, public string $username, public string $password)
    {
        $server = new Server($host);
        $this->connection = $server->authenticate($username, $password);
    }

    public function fetchMails($since){
        $mailbox = $this->connection->getMailbox('INBOX');
        $messages = $mailbox->getMessages(
            new Since($since),
            \SORTDATE, // Sort criteria
            true // Descending order
        );

        $mails = [];
        foreach ($messages as $key=>$message) {
            $mails[$key]['message_id'] = $message->getId();
            $mails[$key]['from'] = $message->getFrom()->getAddress();
            $mails[$key]['from_name'] = $message->getFrom()->getName();
            $tos = $message->getTo();
            $to_addresses = [];
            foreach($tos as $to){
                $to_addresses[] = $to->getAddress();
            }
            $mails[$key]['to'] = implode(', ', $to_addresses);
            $mails[$key]['subject'] = $message->getSubject();
            $body = $message->getCompleteBodyHtml();
            if ($body === null) {
                $body = $message->getCompleteBodyText();
            }
            $mails[$key]['body'] = $body;
            $mails[$key]['message_date'] = $message->getDate()->format('Y-m-d H:i:s');
            if(count($message->getAttachments())){
                $attachments = [];
                foreach ($message->getAttachments() as $attachment) {
                    @file_put_contents(public_path('uploads/email/attachments/gmail/').time().'_' . $attachment->getFilename(), $attachment->getDecodedContent());
                    $attachments[] = 'uploads/email/attachments/gmail/'.time().'_'.$attachment->getFilename();
                }
                $mails[$key]['attachments'] = $attachments;
            }
        }

        return $mails;
    }
    
}