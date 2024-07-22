<?php

namespace App\Services;

use App\Http\Resources\WhatsappTemplateResource;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;

class WhatsappTemplateService{

    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new WhatsappTemplate();
        $obj->fill($inputData);
        if($obj->save()){
            return new WhatsappTemplateResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function update(Request $request){
        $obj = WhatsappTemplate::find($request->id);
        if(!$obj)
            return response()->json(['message' => 'Invalid Request'], 400);

        $inputData = $request->all();
        if($obj->update($inputData)){
            return new WhatsappTemplateResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }
}