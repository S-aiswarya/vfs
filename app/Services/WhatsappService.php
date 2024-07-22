<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\CommunicationLogResource;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Traits\S3;
use App\Traits\BCIE;
use App\Models\CommunicationLog;
use App\Models\WhatsappTemplate;
use App\Services\Whatsapp\Whatsapp;

class WhatsappService{
    use S3, BCIE;

    public function __construct(protected Whatsapp $whatsapp){
        
    }

    public function store(Request $request)
    {
        $inputData = $request->all();
        $template = WhatsappTemplate::where('id', $inputData['template_id'])->where('approved', 1)->first();
        if($template)
        {
            $obj = new CommunicationLog();
            $inputData['type'] = "Whatsapp Send";
            $inputData['message_date'] = date('Y-m-d H:i:s');
            $obj->fill($inputData);
            if($obj->save()){
                if($obj->lead)
                    $this->createTimeline('whatsapp_send', $obj->lead);
                $this->sendMessage($obj, $template);
                return new CommunicationLogResource($obj);
            }
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function storeMessage($message){
        $check_lead_exist = Lead::where(DB::raw("CONCAT('phone_country_code','phone_number')=".$message['from']))
                                    ->orWhere(DB::raw("CONCAT('alternate_phone_country_code','alternate_phone_number')=".$message['from']))
                                    ->orWhere(DB::raw("CONCAT('whatsapp_country_code','whatsapp_number')=".$message['from']))
                                    ->first();
        if($check_lead_exist)
            $lead_id = $check_lead_exist->id;
        else{
            $lead_service = new LeadService;
            $lead_id = $lead_service->basicStore($message);
        }

        if(!$lead_id)
            return;

        $obj = new CommunicationLog();
        $obj->type = "Whatsapp";
        $obj->lead_id = $lead_id;
        $obj->message_id = $message['id'];
        $obj->from = $message['from'];
        $obj->message = $message['text']['body'];
        $obj->message_date = date('Y-m-d H:i:s', $message['timestamp']);
        $obj->save();
    }

    protected function sendMessage($obj, $template){
        

        $tos = $obj->to;
        $to_array = explode(',', $tos);
        $tos = array_map(function($to){
            return trim($to);
        }, $to_array);

        foreach($tos as $to){
            $params = $this->processTemplateData($obj, $template->content);
            $this->whatsapp->to($to)->template($template->template_name, $params)->send();
        }
        return true;
    }

    protected function processTemplateData($obj, $template){
        $replace_array = [];
        $has_data = preg_match_all("/{{(.*?)}}/i", $template, $matches);
        if($has_data && $obj->lead){
            list($search_array, $replace_array) = $this->leadData($obj->lead, $matches[0]);
        }
        return $replace_array;
    }

    protected function createTimeline($type, $lead){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'email_send':
                $description = "Email send by {$user}";
                break;
            case 'email_send':
                $description = "New email received";
                break;
        }
        if($description)
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
    }
}