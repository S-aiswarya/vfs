<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function fileUpload($file, $file_path){
        $data = ['media_path'=>null, 'media_type'=>null, 'mime_type'=>null];
        if($file->isValid())
        {
            $data['media_type'] = $this->getFileType($file);
            $data['mime_type'] = $file->getMimeType();
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $file->storeAs($file_path, $fileNameToStore, ['disk' => 'public']);
            $data['media_path'] = 'uploads/'.$path;
        }
        return $data;
    }

    protected function getFileType($file){
        $type = "File";
        if(substr($file->getMimeType(), 0, 5) == 'image') {
            $type = "Image";
        }
        else if(substr($file->getMimeType(), 0, 5) == 'video') {
            $type = "Video";
        }
        else if(substr($file->getMimeType(), 0, 5) == 'audio') {
            $type = "Audio";
        }
        else if($file->getMimeType() == 'application/msword') {
            $type = "DOC";
        }
        else if($file->getMimeType() == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $type = "DOCX";
        }
        else if($file->getMimeType() == 'application/vnd.ms-excel') {
            $type = "XLS";
        }
        else if($file->getMimeType() == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $type = "XLSX";
        }
        else if($file->getMimeType() == 'application/vnd.ms-powerpoint') {
            $type = "PPT";
        }
        else if($file->getMimeType() == 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
            $type = "PPTX";
        }
        else if($file->getMimeType() == 'application/pdf') {
            $type = "PDF";
        }
        return $type;
    }

    protected function getApiGuard(){
        $guard = null;
        if (auth()->user()->tokenCan('role:user')) {
            $guard = 'user';
        }
        elseif(auth()->user()->tokenCan('role:student')){
            $guard = 'student';
        }
        return $guard;
    }

    protected function getAuthOffices(){
        return DB::table('office_user')->where('office_user.user_id', auth()->user()->id)->pluck('office_id')->toArray();
    }

    protected function getAuthOfficeUsers($offices=null){
        if(!$offices)
            $offices = $this->getAuthOffices();

        return DB::table('users')
                    ->join('office_user', 'office_user.user_id', '=', 'users.id')
                    ->whereIn('office_user.office_id', $offices)->pluck('users.id')->toArray();
    }

    protected function getUserRole(){
        $role = auth()->user()->role;
        return $role->id;
    }
}
