<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\ApplicationUniversityDocument;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Traits\S3;
use App\Traits\BCIE;
use App\Enum\StageType;
use DB;

class ApplicationService{
    use S3, BCIE;
    
    public function store(Request $request)
    {
        $inputData = $request->all();
        $documents = !empty($inputData['documents'])?$inputData['documents']:[];

        $obj = new Application();
        $obj->fill($inputData);
        if($obj->save()){
            $application_stage = $this->getStageByActionType(StageType::ApplicationCreated);
            $this->updateStage($application_stage, $obj);            
            $this->createTimeline('application_created', $obj->lead, $obj);
            return new ApplicationResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    protected function saveDocument($application, $documents=[]): void
    {
        $document_array = [];
        if($documents)
            foreach($documents as $key=>$document){
                $document_array[$document] = ['created_by'=>auth()->user()->id, 'updated_by'=>auth()->user()->id, 'created_at'=>date('Y-m-d H:i:s')];
            }

        $application->documents()->sync($document_array);
    }

    public function update(Request $request){

        $inputData = $request->all();
        $documents = !empty($inputData['documents'])?$inputData['documents']:[];

        $obj = Application::find($request->id);
        if(!$obj)
            return response()->json(['message' => 'Invalid Request'], 400);

        
        if($obj->update($inputData)){
            $this->saveDocument($obj, $documents);
            $this->createTimeline('application_updated', $obj->lead, $obj);
            return new ApplicationResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function changeStage(Request $request, $item){

        if($item = $this->updateStage($request->stage, $item, $request->note)){
            return "Application stage has been updated to {$item->stage->name}";
        }
        else
            return false;
    }

    public function updateStage($stage_id, $application, $note=null){
        $application->stage_id = $stage_id;
        $application->stage_note = $note;
        if($application->save()){
            $this->saveStatusHistory($application->id, $application->stage_id, $note);
            $stage_serivce = new StageService();
            $stage_serivce->taskAction($application->stage_id, $application->lead, $application);
            $stage_serivce->emailAction($application->stage_id, $application->lead, $application);
            $this->createTimeline('application_status_updated', $application->lead, $application);
            return $application;
        }
        return false;
    }

    public function uploadUniversityDocument(Request $request, $application){
        if ($request->hasFile('document')) {
            $upload = $this->fileUploadS3($request->file('document'), 'applications/documents');
            if($upload['file']){
                $document = new ApplicationUniversityDocument();
                $document->application_id = $application->id;
                $document->document_template_id = $request->document_template_id;
                $document->document = $upload['file'];
                if($document->save()){
                    $possible_next_stages = $application->stage->nextPossibleStages->pluck('id')->toArray();
                    if($request->stage && count($possible_next_stages) && in_array($request->stage, $possible_next_stages)){
                        $this->changeStage($request, $application);
                    }
                    else{
                        if($request->application_number){
                            $application->application_number = $request->application_number;
                        }
                
                        if($request->deposit_amount){
                            $application->deposit_amount_paid = $request->deposit_amount;
                            $application->deposit_paid_on = ($request->deposit_paid_on)?date('Y-m-d H:i:s', strtotime($request->deposit_paid_on)):null;
                        }
                        $application->save();
                    }
                    $application->document = $document;
                    $this->createTimeline('application_university_document_uploaded', $application->lead, $application);
                    return response()->json(['message' => $application->document->documentTemplate->name.' has been uploaded successfully.']);
                }                    
            }
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function deleteUniversityDocument(Request $request, $item){
        $application = $item->application;
        $application->document_template =  $item->documentTemplate;
        if($item->delete()){
            $this->createTimeline('application_university_document_deleted', $application->lead, $application);
            return response()->json(['message' => $application->document_template->name.' has been deleted successfully.']);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function saveUniversityId($application, $uni_id){
        $application->application_number = $uni_id;
        if($application->save()){
            $this->createTimeline('application_uni_id_added', $application->lead, $application);
            return response()->json(['message' => "University id has been successfully added."]);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    protected function saveStatusHistory($application_id, $new_status, $note=null){
        $status = new ApplicationStatus();
        $status->application_id = $application_id;
        $status->stage_id = $new_status;
        $status->note = $note;
        $status->save();
    }

    protected function createTimeline($type, $lead, $application){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'application_created':
                $description = "Application has been created by {$user}";
                break;

            case 'application_updated':
                $description = "Application has been updated by {$user}";
                break;

            case 'application_status_updated':
                $description = "Application stage has been updated to {$application->stage->name} by {$user}";
                break;

            case 'application_university_document_uploaded':
                $description = $application->document->documentTemplate->name." has been uploaded by {$user}";
                break;

            case 'application_university_document_deleted':
                $description = $application->document_template->name." has been deleted by {$user}";
                break;

            case 'application_uni_id_added':
                $description = "University id ".$application->application_number." has been added by {$user}";
                break;

            case 'application_deposit_added':
                $date = date('d M, Y h:i A', strtotime($application->deposit_paid_on));
                $description = "A deposite amount of {$application->deposit_amount_paid} has been added on {$date} by {$user}";
                break;

            case 'differ_intake':
                $description = "Intake has been changed from {$application->old_intake->month} {$application->old_intake->year} to {$application->intake->month} {$application->intake->year} by {$user}";
                break;

            case 'application_assigned_to_coordinator':
                $description = "Application has been assigned to {$application->appCoordinator->name} by {$user}";
                break;

            case 'application_returned_to_counsellor':
                $description = "Application has been returned to {$application->lead->assignedToCounsellor->name} by {$user}";
                break;

            case 'lead_app_coordinator_changed':
                $description = "Application coordinator has been changed from {$application->old_app_coordinator->name} to {$application->appCoordinator->name}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
            TimelineChanged::dispatch($type, $description, Application::class, $application->id, request()->post());
        }
    }
}