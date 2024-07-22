<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SpiderMailer;

class SendDocumentRequestNotifiication
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $lead = $event->lead;
        SpiderMailer::to($lead->email)->template('send_document_request_notification')->content($lead, 'lead')->send();
    }
}
