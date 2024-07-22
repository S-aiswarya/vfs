<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Traits\S3;
use DB;

class ApplicationService{
    use S3;
    
    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new Application();
        $obj->fill($inputData);
        if($obj->save()){
            $documents = !empty($inputData['documents'])?$inputData['documents']:[];
            $this->saveDocument($obj, $documents);
            $this->createTimeline('application_created', $obj->student->lead, $obj);
            $this->saveStatusHistory($obj->id, 'Created');
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
        $obj = Application::find($request->id);
        if(!$obj)
            return response()->json(['message' => 'Invalid Request'], 400);

        $inputData = $request->all();
        if($obj->update($inputData)){
            $documents = !empty($inputData['documents'])?$inputData['documents']:[];
            $this->saveDocument($obj, $documents);
            $this->createTimeline('application_updated', $obj->student->lead, $obj);
            return new ApplicationResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function statusAppliedInUniversity(Request $request, $item){

        $item->application_number = $request->application_number;
        $item->status = "Applied in University";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            return response()->json(['message' => 'Application status changed to Applied in University.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusUniversityRejected(Request $request, $item){
       
        $item->status = "University Rejected";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            return response()->json(['message' => 'Application status changed to University Rejected.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusUniversityAccepted(Request $request, $item){
        
        $item->status = "University Accepted";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            $this->uploadAcceptanceLetter($request, $item);
            return response()->json(['message' => 'Application status changed to University Accepted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusCasApproved(Request $request, $item){
        
        $item->status = "CAS Approved";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            $this->uploadCasDocument($request, $item);
            return response()->json(['message' => 'Application status changed to CAS Approved.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusCasRejected(Request $request, $item){
        
        $item->status = "CAS Rejected";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            return response()->json(['message' => 'Application status changed to CAS Rejected.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusVisaApplied(Request $request, $item){
        
        $item->status = "Visa Applied";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            return response()->json(['message' => 'Application status changed to Visa Applied.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusVisaApproved(Request $request, $item){
        
        $item->status = "Visa Approved";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            return response()->json(['message' => 'Application status changed to Visa Approved.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusVisaRejected(Request $request, $item){
        
        $item->status = "Visa Rejected";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            return response()->json(['message' => 'Application status changed to Visa Rejected.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusUniversityFeePaid(Request $request, $item){
        
        $item->status = "University Fee Paid";
        if($item->save()){
            $this->saveStatusHistory($item->id, $item->status);
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            $this->uploadFeeReceipt($request, $item);
            return response()->json(['message' => 'Application status changed to University fee paid.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function statusAdmissionCompleted(Request $request, $item){

        DB::beginTransaction();
        try {
            $item->status = "Admission Completed";
            if($item->save()){
                $student = $item->student;
                $student->closed = 1;
                $student->save();
    
                $lead = $student->lead;
                $lead->closed = 1;
                $lead->save();
            }
            DB::commit();
            $this->createTimeline('application_status_updated', $item->student->lead, $item);
            $this->saveStatusHistory($item->id, $item->status);
            return response()->json(['message' => 'Application status changed to Admission Completed.']);
    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error'], 500);
        }

    }

    public function uploadAcceptanceLetter(Request $request, $item){
        if ($request->hasFile('acceptance_letter')) {
            $upload = $this->fileUploadS3($request->file('acceptance_letter'), 'applications/acceptance_letters');
            if($upload['file']){
                $item->acceptance_letter = $upload['file'];
                if($item->save()){
                    $this->createTimeline('application_acceptance_letter_uploaded', $item->student->lead, $item);
                    return response()->json(['message' => 'Acceptance letter has been uploaded successfully.']);
                }                    
            }
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function uploadCasDocument(Request $request, $item){
        if ($request->hasFile('cas_document')) {
            $upload = $this->fileUploadS3($request->file('cas_document'), 'applications/cas_documents');
            if($upload['file']){
                $item->cas_document = $upload['file'];
                if($item->save()){
                    $this->createTimeline('application_cas_document_uploaded', $item->student->lead, $item);
                    return response()->json(['message' => 'CAS document has been uploaded successfully.']);
                }                    
            }
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function uploadFeeReceipt(Request $request, $item){
        if ($request->hasFile('fee_receipt')) {
            $upload = $this->fileUploadS3($request->file('fee_receipt'), 'applications/fee_receiptes');
            if($upload['file']){
                $item->fee_receipt = $upload['file'];
                if($item->save()){
                    $this->createTimeline('application_fee_receipt_uploaded', $item->student->lead, $item);
                    return response()->json(['message' => 'Fee receipt has been uploaded successfully.']);
                }                    
            }
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function deleteAcceptanceLetter(Request $request, $item){
        $item->acceptance_letter = null;
        if($item->save()){
            $this->createTimeline('application_acceptance_letter_deleted', $item->student->lead, $item);
            return response()->json(['message' => 'Acceptance letter has been deleted successfully.']);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function deleteCasDocument(Request $request, $item){
        $item->cas_document = null;
        if($item->save()){
            $this->createTimeline('application_cas_document_deleted', $item->student->lead, $item);
            return response()->json(['message' => 'CAS document has been deleted successfully.']);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function deleteFeeReceipt(Request $request, $item){
        $item->fee_receipt = null;
        if($item->save()){
            $this->createTimeline('application_fee_receipt_deleted', $item->student->lead, $item);
            return response()->json(['message' => 'Fee receipt has been deleted successfully.']);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    protected function saveStatusHistory($application_id, $new_status){
        $status = new ApplicationStatus();
        $status->application_id = $application_id;
        $status->status = $new_status;
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
                $description = "Application status has been updated to {$application->status} by {$user}";
                break;

            case 'application_acceptance_letter_uploaded':
                $description = "Application acceptance letter has been uploaded by {$user}";
                break;

            case 'application_cas_document_uploaded':
                $description = "Application cas document has been uploaded by {$user}";
                break;

            case 'application_fee_receipt_uploaded':
                $description = "Application fee receipt has been uploaded by {$user}";
                break;

            case 'application_acceptance_letter_deleted':
                $description = "Application acceptance letter has been deleted by {$user}";
                break;
    
            case 'application_cas_document_deleted':
                $description = "Application cas document has been deleted by {$user}";
                break;
    
            case 'application_fee_receipt_deleted':
                $description = "Application fee receipt has been deleted by {$user}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
            TimelineChanged::dispatch($type, $description, Application::class, $application->id, request()->post());
        }
    }
}