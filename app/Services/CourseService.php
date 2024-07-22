<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Traits\S3;
use Auth;

class CourseService{
    use S3;
    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new Course();
        if(Auth::getDefaultDriver() == 'admin'){
            $inputData['created_by_admin'] = $inputData['updated_by_admin'] = auth()->user()->id;
        }
        elseif(Auth::getDefaultDriver() == 'sanctum'){
            $inputData['created_by_user'] = $inputData['updated_by_user'] = auth()->user()->id;
        }
        $inputData['image'] = $this->updateImage($request);
        $obj->fill($inputData);
        $obj->save();
        return $obj;
    }

    public function updateImage($request, $current_file=null){
        $image = null;
        if($request->image_removed && $current_file){
            $this->fileDeleteS3($current_file);
            $current_file = null;
        }
        if ($request->hasFile('image')) {
            $upload = $this->fileUploadS3($request->file('image'), 'course/images');
            if($upload['file'])
                $image = $upload['file'];
        }
        else
            $image = $current_file;
        return $image;
    }

    public function update(Request $request, $id){
        $obj = Course::find($id);
        if(!$obj)
            return null;

        $inputData = $request->all();
        if(Auth::getDefaultDriver() == 'admin'){
            $inputData['updated_by_admin'] = auth()->user()->id;
        }
        elseif(Auth::getDefaultDriver() == 'sanctum'){
            $inputData['updated_by_user'] = auth()->user()->id;
        }
        $inputData['image'] = $this->updateImage($request, $obj->image);
        if($obj->update($inputData)){
            return $obj;
        }
        return null;
    }
}