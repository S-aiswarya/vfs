<?php

namespace App\Services;


use App\Http\Resources\Apis\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserService{
    

    public function update(User $item, $request)
    { 
         

        $item->center_id=$request->center_id;
        $item->gate_id=$request->gate_id;
        $item->save();

        return new UserResource($item);
    }

}