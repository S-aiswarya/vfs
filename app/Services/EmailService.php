<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\CommunicationLogResource;
use App\Models\EmailAttachment;
use App\Models\Lead;
use Illuminate\Http\Request;
use SpiderMailer;
use App\Traits\S3;
use App\Traits\BCIE;
use App\Services\LeadService;
use App\Models\UniversityEmail;
use App\Models\AgencyEmail;
use App\Models\CommunicationLog;
use App\Models\UniversityContact;

class EmailService{
    use S3, BCIE;

    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new CommunicationLog();
        $inputData['type'] = "Send";
        $inputData['message_date'] = date('Y-m-d H:i:s');
        $obj->fill($inputData);
        if($obj->save()){
            if($obj->lead)
                $this->createTimeline('email_send', $obj->lead);
            $this->saveAttachments($request, $obj);
            $this->sendMail($obj);
            return new CommunicationLogResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function storeReceivedEmails($mails){
        foreach($mails as $mail){
            $from_address_original = $mail['from'];
            if($is_university = $this->checkInUniversityEmails($from_address_original)){
                $mail_content = $this->pharseMailBody($mail['body']);
                if($mail_content && !empty($mail_content['email']))
                    $from_address = $mail_content['email'];
                else
                    break;
            }
            elseif($is_agency = $this->checkInAgencyEmails($from_address_original)){
                $mail_content = $this->pharseMailBody($mail['body']);
                if($mail_content && !empty($mail_content['email']))
                    $from_address = $mail_content['email'];
                else
                    break;
            }
            else{
                $from_address = $from_address_original;
                $mail_content = ['name'=> $mail['from_name'], 'email' => $mail['from']];
            }
            
            $check_exist = CommunicationLog::where('message_id', $mail['message_id'])->where('from_address', $from_address)->first();
            if(!$check_exist){
                $check_lead_exist = Lead::where('email', $from_address)->first();
                if($check_lead_exist)
                    $lead_id = $check_lead_exist->id;
                else{
                    $lead_service = new LeadService;
                    $lead_id = $lead_service->basicStore($mail_content);
                }

                if(!$lead_id)
                    break;
                $communication_log = new CommunicationLog();
                $communication_log->lead_id = $lead_id;
                $communication_log->message_id = $mail['message_id'];
                $communication_log->from_original = $from_address_original;
                $communication_log->from = $from_address;
                $communication_log->to = $mail['to'];
                $communication_log->subject = $mail['subject'];
                $communication_log->message = $mail['body'];
                $communication_log->message_date = $mail['message_date'];
                if($is_university)
                    $communication_log->from_university_id = $is_university->id;
                if($is_agency)
                    $communication_log->from_agency_id = $is_agency->id;
                if($communication_log->save()){
                    if($communication_log->lead)
                        $this->createTimeline('email_received', $communication_log->lead);
                    if(!empty($mail['attachments'])){
                        foreach($mail['attachments'] as $aFile){
                            if(!empty($aFile)){
                                $this->storeFile($aFile, $communication_log->id);
                            }
                        }
                    }
                }
            }
        }
    }

    protected function pharseMailBody($html){
        $html = new \Html2Text\Html2Text($html);
        $text_string = $html->getText();
        $content_array = preg_split("/\r\n|\n|\r/", $text_string);

        $valid_strings = ['name', 'phone', 'phone number', 'mobile', 'mobile number', 'email'];
        $content_array = array_filter($content_array, function($content) use($valid_strings){
            foreach($valid_strings as $string){
                if(str_contains(strtolower($content), $string))
                    return $content;
            }
        });

        $output = ['name' => '', 'phone_number'=>'', 'email'=>''];
        foreach($content_array as $content){
            $content = trim($content);
            $name_pattern = "/^name(:)?/i";
            if(preg_match($name_pattern, $content)) {
                $output['name'] =  trim(preg_replace($name_pattern, '', $content));
            }
            $phone_pattern = "/^(phone|mobile)( number)?(:)?/i";
            if(preg_match($phone_pattern, $content)) {
                $output['phone_number'] =  trim(preg_replace($phone_pattern, '', $content));
            }
            $email_pattern = "/^email(:)?/i";
            if(preg_match($email_pattern, $content)) {
                $output['email'] =  trim(preg_replace($email_pattern, '', $content));
            }
        }

        return $output;
    }

    protected function checkInUniversityEmails($email){
        $university_email = UniversityContact::where('email', $email)->first();
        return $university_email;
    }

    protected function checkInAgencyEmails($email){
        $agency_email = AgencyEmail::where('email', $email)->first();
        return $agency_email;
    }

    public function saveAttachments($request, $obj){
        if($request->attachments){
            foreach($request->attachments as $attachment){
                $upload = $this->fileUploadS3($attachment, 'email/attachments');
                if($upload['file']){
                    $this->storeFile($upload['file'], $obj->id);
                }
            }
        }
        if($request->attachment_files){
            foreach($request->attachment_files as $aFile){
                if(!empty($aFile)){
                    $this->storeFile($aFile, $obj->id);
                }
            }
        }
    }

    protected function storeFile($file, $id){
        $attachment = new EmailAttachment();
        $attachment->email_id = $id;
        $attachment->file_path = $file;
        $attachment->save();
    }

    public function storeEmailAction($task_action, $send_to, $lead, $application=null){

        foreach($send_to as $email){
            $templateObj = new ActionService;
            $email_template = $templateObj->emailTemplate($task_action->email_template_id, $lead, 'Lead');

            $email = new CommunicationLog();
            $email->lead_id = $lead->id;
            $email->application_id = $application?->id;
            $email->type = "Send";
            $email->to = $email;
            $email->cc = array_merge($email_template['default_cc'], [$task_action->cc_to]);
            $email->subject = $email_template['subject'];
            $email->message = $email_template['body'];
            if($email->save()){
                if(count($task_action->emailTemplate->attachments)){
                    foreach($task_action->emailTemplate->attachments as $attachment){
                        $this->storeFile($attachment->file_path, $email->id);
                    }
                }
                $this->createTimeline('email_send', $email->lead);
                $this->sendMail($email);
            }
        }
    }

    protected function sendMail($obj){
        $data = ['subject' => $obj->subject, 'body' => $obj->message];
        if($obj->attachments && count($obj->attachments)){
            $attachments = $obj->attachments->map(function($attachment){
                if(!empty($attachment->file_path))
                    return public_path($attachment->file_path);
            });

            $data['attachments'] = $attachments->toArray();
        }
        $mail = SpiderMailer::to($obj->to);
        if($obj->cc){
            $mail->cc($this->formatEmails($obj->cc));
        }
        $mail->content($data)->send();
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