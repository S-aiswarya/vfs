<?php

namespace App\Services;


use App\Http\Resources\Apis\CheckinResource;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class VisitorLogService{
    public function store(array $inputData){
        $obj = new CheckIn;
       
        if(!empty($inputData['checkin time']) )
        {
            $inputData['checkin time']= date("Y-m-d H:i:s");
        }
        $obj->fill($inputData);
        if($obj->save()){
            $token = (auth()->user()->center)?auth()->user()->center->token_prefix:'VFS'.$obj->id;
            $obj->token=$token;
            $obj->save();
            return new CheckinResource($obj);
        }
    }
    }

