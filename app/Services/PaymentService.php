<?php

namespace App\Services;

use App\Events\TimelineChanged;
use App\Http\Resources\PaymentResource;
use App\Models\Application;
use App\Models\Lead;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Traits\S3;

class PaymentService{

    use S3;
    public function store(Request $request)
    {
        $inputData = $request->all();
        $obj = new Payment();
        if(empty($inputData['student_id'])){
            $lead = Lead::find($inputData['lead_id']);
            $inputData['student_id'] = $lead->user?->student?->id;
        }
        $inputData['receipt_file'] = $this->updateReceipt($request);
        $obj->fill($inputData);
        if($obj->save()){
            $this->createTimeline('payment_created', $obj->lead, $obj->application_id);
            return new PaymentResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function updateReceipt($request, $current_file=null){
        $file = null;
        if($request->receipt_removed && $current_file){
            $this->fileDeleteS3($current_file);
            $current_file = null;
        }
        if ($request->hasFile('receipt')) {
            $upload = $this->fileUploadS3($request->file('receipt'), 'payments/receipts');
            if($upload['file'])
                $file = $upload['file'];
        }
        else
            $file = $current_file;
        return $file;
    }

    public function update(Request $request){
        $obj = Payment::find($request->id);
        if(!$obj)
            return response()->json(['message' => 'Invalid Request'], 400);

        $inputData = $request->all();
        $inputData['receipt_file'] = $this->updateReceipt($request, $obj->receipt_file);
        if($obj->update($inputData)){
            $this->createTimeline('payment_updated', $obj->lead, $obj->application_id);
            return new PaymentResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $lead, $application_id=null){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'payment_created':
                $description = "Payment has been created by {$user}";
                break;

            case 'payment_updated':
                $description = "Payment has been updated by {$user}";
                break;
        }
        if($description){
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
            if($application_id)
                TimelineChanged::dispatch($type, $description, Application::class, $application_id, request()->post());
        }
    }
}