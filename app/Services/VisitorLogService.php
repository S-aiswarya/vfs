<?php

namespace App\Services;


use App\Http\Resources\Apis\CheckinResource;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class VisitorLogService{
    public function store(array $inputData){
        $obj = new CheckIn;
       
        if(empty($inputData['entry_time']) )
        {
            $inputData['entry_time']= date("Y-m-d H:i:s");
        }
        $inputData['location_id'] = auth()->user()?->location?->id;
        $inputData['center_id'] = auth()->user()?->center?->id;
        $inputData['gate_id'] = auth()->user()?->gate?->id;
        $obj->fill($inputData);
        if($obj->save()){
            $obj->refresh();
            $token = (auth()->user()?->center)?auth()->user()->center->token_prefix:'VFS'.$obj->id;
            $obj->token=$token;
            $obj->save();
            
            return new CheckinResource($obj);
        }
    }

    public function update(CheckIn $item, $note=null)
    {
        $item->exit_time = date('Y-m-d H:i:s');
        $item->checkout_note = $note;
        $item->save();
        return new CheckinResource($item);
    }

}