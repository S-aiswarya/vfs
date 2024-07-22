<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailRequest;
use App\Http\Resources\CommunicationLogResource;
use App\Http\Resources\CommunicationLogResourceCollection;
use App\Http\Resources\EmailTemplateResource;
use App\Models\CommunicationLog as Email;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\ActionService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use DB;

class EmailController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = Email::where('type', 'Send');

        if(!empty($data['lead_id']))
            $items = $items->where('lead_id', $data['lead_id']);

        if(!empty($data['application_id']))
            $items = $items->where('application_id', $data['application_id']);

        if(!empty($data['keyword'])){
            $keyword = $data['keyword'];
            $items = $items->where(function($query) use($keyword){
                $query->where('from', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('from_original', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('to', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('cc', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('subject', 'LIKE', '%'.$keyword.'%');
            });
        }

        if(!empty($data['type']))
            $items = $items->where('type', $data['type']);

        if(!empty($data['created_by']))
            $items = $items->where('created_by', $data['created_by']);

        if(isset($data['from']) || isset($data['to']))
        {
            $from = (isset($data['from']) && trim($data['from']) != "")?date('Y-m-d', strtotime($data['from'])):date('Y').'-'.date('m').'-01';
            $to = (isset($data['to']) && trim($data['to']) != "")?date('Y-m-d', strtotime($data['to'])):date('Y-m-d');
            $items = $items->whereBetween(DB::raw('DATE(created_at)'), array($from, $to));
        }

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new CommunicationLogResourceCollection($items);
    }

    public function view($id)
    {
        $item = Email::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new CommunicationLogResource($item);
    }

    public function store(EmailRequest $request, EmailService $service){
        $request->validated();
        return $service->store($request);
    }

    public function template($template_id, $id, $type="lead"){
        $template = EmailTemplate::find($template_id);
        if(!$template)
            return response()->json(['message' => 'Invalid Request'], 400);
        if($type == "lead"){
            $data = Lead::find($id);
            if(!$data)
                return response()->json(['message' => 'Invalid Request'], 400);
        }
        $templateObj = new ActionService;
        $template->template = $templateObj->emailTemplate($template->id, $data, $type);
        return new EmailTemplateResource($template);
    }

}
