<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\WhatsappTemplate;
use App\Traits\BCIE;
use DB;

class ActionService
{
    use BCIE;
    public function process($action, $type, $data)
    {
        $action = DB::table('actions')->where('action', $action)->where('template_type', 'email')->first();
        $mailer = [];
        if($action){
            $mailer = $this->emailTemplate($action->template_id, $data, $type);
        }
        return $mailer;
    }

    public function emailTemplate($template_id, $data, $type="lead"){
        $template = EmailTemplate::find($template_id);
        $output = [];
        if($template){
            if($type == "user")
                list($search_array, $replace_array) = $this->userData($data);
            elseif($type == "lead")
                list($search_array, $replace_array) = $this->leadData($data);
            elseif($type == "application"){
                $application_data = $this->applicationData($data);
                $lead_data = $this->leadData($data->lead);
                $search_array = array_merge($application_data[0], $lead_data[0]);
                $replace_array = array_merge($application_data[1], $lead_data[1]);
            }

            $output['subject'] = str_replace($search_array, $replace_array, $template->subject);
            $output['body'] = str_replace($search_array, $replace_array, $template->body);
            $output['body_footer'] = str_replace($search_array, $replace_array, $template->body_footer);
            $output['default_cc'] = ($template->default_cc)?$this->formatEmails($template->default_cc):[];
        }
        
        return $output;
    }

    public function whatsappTemplate($template_id, $data, $type="lead"){
        $template = WhatsappTemplate::find($template_id);
        $output = [];
        if($template){
            if($type == "user")
                list($search_array, $replace_array) = $this->userData($data);
            elseif($type == "lead")
                list($search_array, $replace_array) = $this->leadData($data);

            $mailer['message'] = str_replace($search_array, $replace_array, $template->content);
        }
        
        return $mailer;
    }
}