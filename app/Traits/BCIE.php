<?php

namespace App\Traits;

use App\Services\StageService;

trait BCIE{
    protected function formatEmails(string $emails):array{
        $email_array = explode(',', $emails);
        $email_ids = array_filter($email_array, function($email){
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        });
        return $email_ids;
    }

    protected function leadData($data, $lookup_array=null) : array {
        $search_array = [];
        $replace_array = [];
        if(!$lookup_array || in_array("{{1}}", $lookup_array)){
            $search_array[] = '{{1}}';
            $replace_array[] = $data->title." ".$data->name;
        }
        if(!$lookup_array || in_array("{{2}}", $lookup_array)){
            $search_array[] = '{{2}}';
            $replace_array[] = $data->email;
        }
        if(!$lookup_array || in_array("{{3}}", $lookup_array)){
            $search_array[] = '{{3}}';
            $replace_array[] = $data->phone_number;
        }
        if(!$lookup_array || in_array("{{4}}", $lookup_array)){
            $search_array[] = '{{4}}';
            $replace_array[] = $data->alternate_phone_number;
        }
        if(!$lookup_array || in_array("{{5}}", $lookup_array)){
            $search_array[] = '{{5}}';
            $replace_array[] = $data->whatsapp_number;
        }
        if(!$lookup_array || in_array("{{6}}", $lookup_array)){
            $search_array[] = '{{6}}';
            $replace_array[] = $data->preferred_destinations;
        }
        if(!$lookup_array || in_array("{{7}}", $lookup_array)){
            $search_array[] = '{{7}}';
            $replace_array[] = $data->preferred_packages;
        }
        if(!$lookup_array || in_array("{{8}}", $lookup_array)){
            $search_array[] = '{{8}}';
            $replace_array[] = $data->leadSource?->name;
        }
        if(!$lookup_array || in_array("{{9}}", $lookup_array)){
            $search_array[] = '{{9}}';
            $replace_array[] = $data->referrance_from;
        }
        if(!$lookup_array || in_array("{{10}}", $lookup_array)){
            $search_array[] = '{{10}}';
            $replace_array[] = $data->note;
        }
        if(!$lookup_array || in_array("{{11}}", $lookup_array)){
            $search_array[] = '{{11}}';
            $replace_array[] = $data->assignedTo?->name;
        }
        if(!$lookup_array || in_array("{{12}}", $lookup_array)){
            $search_array[] = '{{12}}';
            $replace_array[] = $data->assignedToOffice?->name;
        }
        if(!$lookup_array || in_array("{{13}}", $lookup_array)){
            $search_array[] = '{{13}}';
            $replace_array[] = $data->stage?->name;
        }
        if(!$lookup_array || in_array("{{14}}", $lookup_array)){
            $search_array[] = '{{14}}';
            $replace_array[] = $data->assignedTo?->address;
        }
        if(!$lookup_array || in_array("{{15}}", $lookup_array)){
            $search_array[] = '{{15}}';
            $replace_array[] = $data->assignedToOffice?->address;
        }
        if(!$lookup_array || in_array("{{00}}", $lookup_array)){
            $search_array[] = '{{00}}';
            $replace_array[] = $data?->plain_password;
        }
        return [$search_array, $replace_array];
    }

    protected function applicationData($data, $lookup_array=null) : array {
        $search_array = [];
        $replace_array = [];
        if(!$lookup_array || in_array("{{30}}", $lookup_array)){
            $search_array[] = '{{30}}';
            $replace_array[] = $data->name;
        }
        if(!$lookup_array || in_array("{{31}}", $lookup_array)){
            $search_array[] = '{{31}}';
            $replace_array[] = $data->email;
        }
        if(!$lookup_array || in_array("{{32}}", $lookup_array)){
            $search_array[] = '{{32}}';
            $replace_array[] = $data->phone_number;
        }
        if(!$lookup_array || in_array("{{33}}", $lookup_array)){
            $search_array[] = '{{33}}';
            $replace_array[] = $data->citizenshipCountry?->name;
        }
        if(!$lookup_array || in_array("{{34}}", $lookup_array)){
            $search_array[] = '{{34}}';
            $replace_array[] = $data->address;
        }
        if(!$lookup_array || in_array("{{35}}", $lookup_array)){
            $search_array[] = '{{35}}';
            $replace_array[] = $data->passport_number;
        }
        if(!$lookup_array || in_array("{{36}}", $lookup_array)){
            $search_array[] = '{{36}}';
            $replace_array[] = $data->passport_exp_date;
        }
        if(!$lookup_array || in_array("{{37}}", $lookup_array)){
            $search_array[] = '{{37}}';
            $replace_array[] = $data->stage->name;
        }
        return [$search_array, $replace_array];
    }

    protected function userData($data){
        $search_array = [];
        $replace_array = [];
        $search_array[] = '{{student_name}}';
        $replace_array[] = $data?->name;
        $search_array[] = '{{student_password}}';
        $replace_array[] = $data?->plain_password;
        return [$search_array, $replace_array];
    }

    protected function getStageByActionType($action_type){
        $stage_service = new StageService();
        return $stage_service->getStageByActionType($action_type);
    }
}