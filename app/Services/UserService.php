<?php

namespace App\Services;


use App\Http\Resources\Apis\UserResource;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Http\Request;

class UserService{
    

    public function update(User $item, $request)
    { 
         
        $item->center_id=$request->center_id;
        $item->gate_id=$request->gate_id;
        $item->save();

        return new UserResource($item);
    }

    public function saveCheckinHistory($ip, $checkin_type, $user_id=null ){
        $obj = new LoginHistory();
        $obj->user_id= ($user_id)?$user_id:auth()->user()->id;
        $obj->action=$checkin_type;
        $obj->action_time=date("Y-m-d H:i:s");
        $obj->ip_address=$ip;
        $obj->save();
        
    }

}