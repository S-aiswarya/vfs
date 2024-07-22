<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsappTemplateRequest;
use App\Http\Resources\EmailTemplateResource;
use App\Http\Resources\WhatsappTemplateResource;
use App\Http\Resources\WhatsappTemplateResourceCollection;
use App\Models\Lead;
use App\Models\WhatsappTemplate;
use App\Services\ActionService;
use App\Services\WhatsappTemplateService;
use Illuminate\Http\Request;

class WhatsappTemplateController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new WhatsappTemplate();

        if(!empty($data['created_by']))
            $items = $items->where('created_by', $data['created_by']);

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new WhatsappTemplateResourceCollection($items);
    }

    public function view($id)
    {
        $item = WhatsappTemplate::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new WhatsappTemplateResource($item);
    }

    public function store(WhatsappTemplateRequest $request, WhatsappTemplateService $service){
        $request->validated();
        return $service->store($request);
    }

    public function update(WhatsappTemplateRequest $request, WhatsappTemplateService $service){
        $request->validated();
        return $service->update($request);
    }

    public function template($template_id, $type, $id)
    {
        $item = WhatsappTemplate::find($template_id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);

        if($type == "lead"){
            $lead = Lead::find($id);
            if(!$lead)
                return response()->json(['message' => 'Invalid Request'], 400);

            $actionService = new ActionService;
            $item->template = $actionService->whatsappTemplate($item->id, $lead);
        }
        return new WhatsappTemplateResource($item);
    }
}
