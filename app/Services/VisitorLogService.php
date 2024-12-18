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
        $inputData['location_id'] = (!empty($inputData['location_id']))?$inputData['location_id']:auth()->user()?->center_location?->id;
        $inputData['center_id'] = (!empty($inputData['center_id']))?$inputData['center_id']:auth()->user()?->center?->id;
        $inputData['gate_id'] = (!empty($inputData['gate_id']))?$inputData['gate_id']:auth()->user()?->gate?->id;
        $obj->fill($inputData);
        if($obj->save()){
            $obj->refresh();
            if(!empty($inputData['token_prefix']))
                $token_prefix = $inputData['token_prefix'];
            else
                $token_prefix = (auth()->user()?->center)?auth()->user()->center->token_prefix:'VFS';
            $token = $token_prefix.$obj->id;
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

     
    public function Visitorlog($item, $request){

            if($item->update($request))
            {
                return new CheckinResource($item);
            }
       
        return response()->json(['message' => 'Error'], 500);
     }


}
