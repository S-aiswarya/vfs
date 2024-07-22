<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait S3{
    protected function fileUploadS3($file, $file_path, $filename=null){
        $data = ['file'=>null, 'file_size'=>null];
        if($file->isValid())
        {
            $file_size =  $file->getSize();
            if(!$filename)
                $filename = time().'_'.$file->getClientOriginalName();
            
            if(config('app.env') != "production"){
                $location = public_path('uploads/'.$file_path);
                if($file->move($location,$filename)){
                    $data['file'] = "uploads/{$file_path}/{$filename}";
                    $data['file_size'] = $file_size;
                    $data['file_path'] = asset($data['file']);
                }
            }
            else{
                Storage::disk('s3')->put($file_path."/".$filename, file_get_contents($file));
                $data['file'] = "{$file_path}/{$filename}";
                $data['file_size'] = $file_size;
                $data['file_path'] = $data['file'];
            }
        }
        return $data;
    }

    protected static function fileDisplayS3($file){
        if(config('app.env') == "production"){
            $file_path = Storage::disk('s3')->temporaryUrl($file, '+1 minutes');
            return $file_path;
        }
        else
            return asset($file);
    }

    protected function fileDeleteS3($file){
        if(config('app.env') != "production"){
            if(file_exists(public_path($file))){
                @unlink(public_path($file));
            }
        }
    }
}