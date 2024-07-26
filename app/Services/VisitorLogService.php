<?php

namespace App\Services;


use App\Http\Resources\Apis\CheckinResource;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class VisitorLogService{
    public function store(array $inputData){
        $obj = new CheckIn;
        $inputData = $this->processData($inputData);
        $obj->fill($inputData);
        if($obj->save())
        return response()->json(['message' => 'Error'], 500);
    }
    }

