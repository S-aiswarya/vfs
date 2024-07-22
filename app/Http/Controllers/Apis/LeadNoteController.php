<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadNoteRequest;
use App\Http\Resources\LeadNoteResource;
use App\Http\Resources\LeadNoteResourceCollection;
use App\Models\FollowUp as LeadNote;
use App\Services\LeadNoteService;
use Illuminate\Http\Request;

class LeadNoteController extends Controller
{
    public function index(Request $request, $lead_id)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = LeadNote::where('lead_id', $lead_id)->where('type', 'Note');

        if(!empty($data['application_id'])){
            $items = $items->where('application_id', $data['application_id']);
        }

        if(!empty($data['created_by'])){
            $items = $items->where('created_by', $data['created_by']);
        }

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new LeadNoteResourceCollection($items);
    }

    public function store(LeadNoteRequest $request, LeadNoteService $service)
    {
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id)
    {
        $item = LeadNote::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new LeadNoteResource($item);
    }

    public function update(LeadNoteRequest $request, LeadNoteService $service)
    {
        $request->validated();
        return $service->update($request->all());
    }


    public function delete(Request $request, LeadNoteService $service)
    {
        return $service->delete($request);
    }
}
