<?php

namespace App\Listeners;

use App\Events\TimelineChanged;
use App\Models\ApiLog;
use App\Models\ApiLogInput;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveApiLog
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
    public function handle(TimelineChanged $event): void
    {
        $api_log = new ApiLog();
        $api_log->type = $event->type;
        $api_log->description = $event->description;
        $api_log->relatable_type = $event->relatable_type;
        $api_log->relatable_id = $event->relatable_id;
        $api_log->created_at = date('Y-m-d H:i:s');
        if($api_log->save()){
            if($event->input){
                $api_log_input = new ApiLogInput();
                $api_log_input->api_log_id = $api_log->id;
                $api_log_input->input = json_encode($event->input);
                $api_log_input->created_at = date('Y-m-d H:i:s');
                $api_log_input->save();
            }
        }
    }
}
