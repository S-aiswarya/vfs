<?php

namespace App\Services;

use App\Events\DocumentRequestCreated;
use App\Events\TimelineChanged;
use App\Http\Resources\DocumentResource;
use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Traits\S3;

class DocumentService{
    use S3;
    
    public function store(Request $request)
    {
        $inputData = $request->all();
        $lead = Lead::find($request->lead_id);
        if(!$lead)
            return response()->json(['message' => 'Invalid Request'], 400);

        $application = null;
        if(!empty($inputData['application_id'])){
            $application = Application::where('id', $inputData['application_id'])->where('lead_id', $inputData['lead_id'])->first();
            if(!$application)
                return response()->json(['message' => 'Invalid Request'], 400);
        }

        $obj = new Document();
        if ($request->hasFile('file')) {
            $file_name = $this->getFileName($lead, $request->title, $request->file('file')->extension(), $application);

            $upload = $this->fileUploadS3($request->file('file'), 'leads/documnets_'.$request->lead_id, $file_name);
            if($upload['file'])
                $inputData['file'] = $upload['file'];
        }
        if(empty($inputData['file']))
            return response()->json(['message' => 'Error'], 500);

        $inputData['uploaded_by'] = auth()->user()->id;
        $inputData['status'] = "Uploaded";
        $obj->fill($inputData);
        if($obj->save()){
            $this->createTimeline('document_created', $obj->lead);
            return new DocumentResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    protected function getFileName($lead, $template_name, $extension, $application=null){
        $file_name = $lead->name;
        if($application)
            $file_name .= "_".$application->name;

        $file_name .= '_'.$template_name.'.'.$extension;
        
        if (file_exists(public_path('uploads/leads/documnets_'.$lead->id.'/'.$file_name))){
            $file_name = $lead->name.'_'.$template_name.'_'.time().'.'.$extension;
        }

        return $file_name;
    }

    public function update(Request $request){
        $obj = Document::find($request->id);
        if(!$obj)
            return response()->json(['message' => 'Invalid Request'], 400);

        $inputData = $request->all();
        if ($request->hasFile('file')) {
            $file_name = $this->getFileName($obj->lead, $request->title, $request->file('file')->extension(), $obj->application);
            $upload = $this->fileUploadS3($request->file('file'), 'leads/documnets_'.$request->lead_id, $file_name);
            if($upload['file']){
                $inputData['file'] = $upload['file'];
                $inputData['uploaded_by'] = auth()->user()->id;
                $inputData['status'] = "Uploaded";
            }
        }
        if($obj->update($inputData)){
            $this->createTimeline('document_updated', $obj->lead);
            return new DocumentResource($obj);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function request(Request $request){
        $inputData = $request->all();
        $lead = Lead::find($inputData['lead_id']);
        if(!$lead)
            return response()->json(['message' => 'Invalid Request'], 400);

        foreach ($inputData['document_template_ids'] as $key => $doc_template) {
            $template = DocumentTemplate::find($doc_template);
            if($template){
                $obj = new Document();
                $obj->lead_id = $inputData['lead_id'];
                $obj->title = $template->name;
                $obj->note = $template->note;
                $obj->document_template_id = $template->id;
                $obj->status = "Requested";
                $obj->save();
            }
        }
        DocumentRequestCreated::dispatch($lead);
        $this->createTimeline('document_requested', $lead);
        return response()->json(['message' => 'Documents has been successfully requested.']);
    }

    public function upload(Request $request, $guard){
        $document = Document::find($request->document_id);
        if(!$document)
            return response()->json(['message' => 'Invalid Request'], 400);

        if($guard == 'student'){
            if($document->lead->student_id != auth()->user()->id)
                return response()->json(['message' => 'Not Authorized'], 401);
        }

        $upload_type = ($document->file)?"modified":"uploaded";

        if ($request->hasFile('file')) {
            $file_name = $this->getFileName($document->lead, $document->title, $request->file('file')->extension());
            $upload = $this->fileUploadS3($request->file('file'), 'leads/documnets_'.$request->lead_id, $file_name);
            if($upload['file'])
                $document->file = $upload['file'];
        }
        if(!$document->file)
            return response()->json(['message' => 'Error'], 500);

        $document->uploaded_by = auth()->user()->id;
        $document->status = "Uploaded";

        if($document->save()){
            $this->createTimeline('document_'.$upload_type, $document->lead);
            return new DocumentResource($document);
        }
        return response()->json(['message' => 'Error'], 500);
    }

    public function accept(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Document::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->status = "Accepted";
        $item->accepted_by = auth()->user()->id;
        $item->accepted_on = date('Y-m-d H:i:s');
        if($item->save())
        {
            $this->createTimeline('document_accepted', $item->lead);
            return response()->json(['message' => 'Document successfully accepted.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    public function reject(Request $request){
        if(!$request->id)
            return response()->json(['message' => 'Invalid Request'], 400);

        $item = Document::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        $item->status = "Rejected";
        $item->rejected_by = auth()->user()->id;
        $item->rejected_on = date('Y-m-d H:i:s');
        if($item->save())
        {
            $this->createTimeline('document_rejected', $item->lead);
            return response()->json(['message' => 'Document successfully rejected.']);
        }
        else
            return response()->json(['message' => 'Error'], 500);
    }

    protected function createTimeline($type, $lead){
        $description = null;
        $user = auth()->user()->name;
        switch ($type) {
            case 'document_created':
                $description = "Document has been added by {$user}";
                break;

            case 'document_requested':
                $description = "Documents has been requested by {$user}";
                break;
            
            case 'document_uploaded':
                $description = "Documents has been uploaded by {$user}";
                break;

            case 'document_modified':
                $description = "Documents has been modified by {$user}";
                break;

            case 'document_accepted':
                $description = "Documents has been accepted by {$user}";
                break;

            case 'document_rejected':
                $description = "Documents has been rejected by {$user}";
                break;

        }
        if($description)
            TimelineChanged::dispatch($type, $description, Lead::class, $lead->id, request()->post());
    }
}