<?php

namespace App\Services;

use App\Models\ReferralLink;
use Illuminate\Http\Request;
use App\Traits\S3;

class ReferralLinkService{

    use S3;

    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new ReferralLink();
        $inputData['token'] = $this->createToken();
        $inputData['last_date_of_validity'] = !empty($inputData['last_date_of_validity'])?date('Y-m-d', strtotime($inputData['last_date_of_validity'])):NULL;
        $inputData['banner_image'] = $this->updateImage($request);
        $obj->fill($inputData);
        $obj->save();
        return $obj;
    }

    protected function createToken(){
        $last_link = ReferralLink::latest()->first();
        $next_id = ($last_link)?$last_link->id:1;
        return md5(uniqid($next_id, true));
    }

    protected function updateImage($request, $current_file=null){
        $image = null;
        if($request->image_removed && $current_file){
            $this->fileDeleteS3($current_file);
            $current_file = null;
        }
        if ($request->hasFile('image')) {
            $upload = $this->fileUploadS3($request->file('image'), 'referral_links/banners');
            if($upload['file'])
                $image = $upload['file'];
        }
        else
            $image = $current_file;
        return $image;
    }

    public function update(Request $request, $id){
        $obj = ReferralLink::find($id);
        if(!$obj)
            return null;

        $inputData = $request->all();
        $inputData['last_date_of_validity'] = !empty($inputData['last_date_of_validity'])?date('Y-m-d', strtotime($inputData['last_date_of_validity'])):NULL;
        $inputData['banner_image'] = $this->updateImage($request, $obj->banner_image);
        if($obj->update($inputData)){
            return $obj;
        }
        return null;
    }
}