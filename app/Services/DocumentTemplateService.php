<?php

namespace App\Services;

use App\Models\DocumentTemplate;

class DocumentTemplateService{

    public function store(array $inputData): ?DocumentTemplate
    {
        $obj = new DocumentTemplate();
        $obj->fill($inputData);
        if($obj->save()){
            return $obj;
        }
        return null;
    }

    public function update(array $inputData): ?DocumentTemplate
    {
        $id = decrypt($inputData['id']);
        if($obj = DocumentTemplate::find($id)){
            if($obj->update($inputData))
                return $obj;
        }   
        return null;
    }
}