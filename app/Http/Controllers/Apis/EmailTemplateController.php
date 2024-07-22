<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailTemplateResource;
use App\Http\Resources\EmailTemplateResourceCollection;
use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use App\Http\Requests\EmailTemplateRequest;
use App\Http\Requests\FileUploadRequest;
use App\Models\Application;
use App\Models\Lead;
use App\Services\ActionService;
use Illuminate\Http\Request;
use App\Traits\S3;

class EmailTemplateController extends Controller
{
    use S3;
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new EmailTemplate();

        $items = $items->where(function($query){
            $query->where('is_private_template', 0)->orWhere('created_by_user', auth()->user()->id);
        });

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new EmailTemplateResourceCollection($items);
    }

    public function view($id)
    {
        $item = EmailTemplate::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new EmailTemplateResource($item);
    }

    public function store(EmailTemplateRequest $request, EmailTemplateService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return new EmailTemplateResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function update(EmailTemplateRequest $request, EmailTemplateService $service)
    {
        $request->validated();
        if($obj = $service->update($request, $request->id)){
            return new EmailTemplateResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function template($template_id, $type, $id)
    {
        $item = EmailTemplate::find($template_id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        if($type == "lead"){
            $lead = Lead::find($id);
            if(!$lead)
                return response()->json(['message' => 'Invalid Request'], 400);

            $actionService = new ActionService;
            $item->template = $actionService->emailTemplate($item->id, $lead);
        }

        if($type == "application"){
            $application = Application::find($id);
            if(!$application)
                return response()->json(['message' => 'Invalid Request'], 400);

            $actionService = new ActionService;
            $item->template = $actionService->emailTemplate($item->id, $application, 'application');
        }
        return new EmailTemplateResource($item);
    }

    public function fileUploads(FileUploadRequest $request){
        $request->validated();
        $upload = $this->fileUploadS3($request->file('file'), 'email_templates/editor/uploads');
        if($upload['file']){
            return response()->json(['data' => $upload]);
        }
        else
            return response()->json(['message' => 'Invalid Request'], 401);
    }
}
