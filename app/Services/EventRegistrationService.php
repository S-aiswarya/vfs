<?php

namespace App\Services;

use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventRegistrationService{
    
    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new EventRegistration();
        $obj->fill($inputData);
        $obj->save();
        return $obj;
    }
}