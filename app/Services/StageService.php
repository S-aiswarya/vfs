<?php

namespace App\Services;

use App\Models\Stage;
use App\Models\StageEmailAction;
use App\Models\StageTaskAction;
use App\Models\University;

class StageService{

    public function store(array $inputData): ?Stage
    {
        $obj = new Stage();
        $inputData['processing_order'] = !empty($inputData['processing_order'])?$inputData['processing_order']:0;
        $obj->fill($inputData);
        if($obj->save()){
            return $obj;
        }
        return null;
    }

    public function update(array $inputData): ?Stage
    {
        $id = decrypt($inputData['id']);
        if($obj = Stage::find($id)){
            $inputData['processing_order'] = !empty($inputData['processing_order'])?$inputData['processing_order']:0;
            if($obj->update($inputData))
                return $obj;
        }   
        return null;
    }

    public function getStageByActionType($action_type){
        return Stage::where('action_type', $action_type)->value('id');
    }

    public function taskAction($stage_id, $lead, $application=null){
        $actions = StageTaskAction::where('stage_id', $stage_id)->where('status', 1)->get();
        if(count($actions)){
            foreach($actions as $action){
                $assign_to_id = $this->getUserIdByType($action->assign_to, $lead);
                if($assign_to_id){
                    $application_id = $application?$application->id:null;
                    $task_service = new TaskService();
                    $task_service->storeTaskAction($action, $assign_to_id, $lead->id, $application_id);
                }
            }
        }
    }

    protected function getUserIdByType($type, $lead){
        $id = null;
        switch ($type) {
            case 'Manager':
                $id = $lead?->assignedToCounsellor?->manager?->id;
                break;
            
            case 'Counsellor':
                $id = $lead?->assignedToCounsellor?->id;
                break;

            case 'Application Coordinator':
                $id = $lead->assign_to_application_manager_id;
                break;
        }

        return $id;
    }

    public function emailAction($stage_id, $lead, $application=null){
        $actions = StageEmailAction::where('stage_id', $stage_id)->where('status', 1)->get();
        if(count($actions)){
            foreach($actions as $action){
                $send_to_email = $this->getUserEmailByType($action->assign_to, $lead, $application);
                if($send_to_email){
                    $email_service = new EmailService();
                    $email_service->storeEmailAction($action, $send_to_email, $lead, $application);
                }
            }
        }
    }

    protected function getUserEmailByType($type, $lead, $application=null){
        $email = [];
        switch ($type) {
            case 'Manager':
                $email[] = $lead?->assignedTo?->manager?->email;
                break;
            
            case 'Sales Person':
                $email[] = $lead?->assignedTo?->email;
                break;

            case 'Lead':
                $email[] = $lead->email;
                break;
        }

        return $email;
    }
}