<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Http\Request;
use Auth;

class EventService{
    
    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new Event();
        if(Auth::getDefaultDriver() == 'admin'){
            $inputData['created_by_admin'] = $inputData['updated_by_admin'] = auth()->user()->id;
        }
        elseif(Auth::getDefaultDriver() == 'sanctum'){
            $inputData['created_by_user'] = $inputData['updated_by_user'] = auth()->user()->id;
        }
        $inputData['token'] = $this->createToken();
        $inputData['start_date'] = !empty($inputData['start_date'])?date('Y-m-d', strtotime($inputData['start_date'])):NULL;
        $inputData['end_date'] = !empty($inputData['end_date'])?date('Y-m-d', strtotime($inputData['end_date'])):NULL;
        $obj->fill($inputData);
        $obj->save();
        return $obj;
    }

    protected function createToken(){
        $last_event = Event::latest()->first();
        $next_id = ($last_event)?$last_event->id:1;
        return md5(uniqid($next_id, true));
    }

    public function update(Request $request, $id){
        $obj = Event::find($id);
        if(!$obj)
            return null;

        $inputData = $request->all();
        if(Auth::getDefaultDriver() == 'admin'){
            $inputData['updated_by_admin'] = auth()->user()->id;
        }
        elseif(Auth::getDefaultDriver() == 'sanctum'){
            $inputData['updated_by_user'] = auth()->user()->id;
        }
        $inputData['start_date'] = !empty($inputData['start_date'])?date('Y-m-d', strtotime($inputData['start_date'])):NULL;
        $inputData['end_date'] = !empty($inputData['end_date'])?date('Y-m-d', strtotime($inputData['end_date'])):NULL;
        if($obj->update($inputData)){
            return $obj;
        }
        return null;
    }
}