<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateAttachment;
use Illuminate\Http\Request;
use Auth;
use App\Traits\S3;

class EmailTemplateService{
    use S3;

    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new EmailTemplate();
        if(Auth::getDefaultDriver() == 'admin'){
            $inputData['created_by_admin'] = $inputData['updated_by_admin'] = auth()->user()->id;
        }
        elseif(Auth::getDefaultDriver() == 'sanctum'){
            $inputData['created_by_user'] = $inputData['updated_by_user'] = auth()->user()->id;
        }
        $obj->fill($inputData);
        if($obj->save()){
            $this->saveAttachments($request, $obj);
        }
        return $obj;
    }

    public function saveAttachments($request, $obj){
        if($request->attachments){
            foreach($request->attachments as $attachment){
                $upload = $this->fileUploadS3($attachment, 'email_templates/attachments');
                if($upload['file']){
                    $attachment = new EmailTemplateAttachment();
                    $attachment->email_template_id = $obj->id;
                    $attachment->file_path = $upload['file'];
                    if(Auth::getDefaultDriver() == 'admin'){
                        $attachment->created_by_admin = $attachment->updated_by_admin = auth()->user()->id;
                    }
                    elseif(Auth::getDefaultDriver() == 'sanctum'){
                        $attachment->created_by_user = $attachment->updated_by_user = auth()->user()->id;
                    }
                    $attachment->save();
                }
            }
        }
    }

    public function update(Request $request, $id){
        $obj = EmailTemplate::find($id);
        if(!$obj)
            return null;

        $inputData = $request->all();
        if(Auth::getDefaultDriver() == 'admin'){
            $inputData['updated_by_admin'] = auth()->user()->id;
        }
        elseif(Auth::getDefaultDriver() == 'sanctum'){
            $inputData['updated_by_user'] = auth()->user()->id;
        }
        if($obj->update($inputData)){
            $this->updateAttachments($request, $obj);
            return $obj;
        }
        return null;
    }

    protected function updateAttachments($request, $obj){
        $attachments = $obj->attachments->pluck('id')->toArray();
        if($request->attachment_ids){
            $attachment_ids = $request->attachment_ids;
            $attachments_to_remove = array_diff($attachments, $attachment_ids);
            if(count($attachments_to_remove)){
                foreach ($attachments_to_remove as $key => $rAttachment) {
                    EmailTemplateAttachment::where('id', $rAttachment)->delete();
                }
            }
        }
        $this->saveAttachments($request, $obj);
    }
}