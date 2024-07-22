<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralLinkRequest;
use App\Http\Resources\ReferralLinkResource;
use App\Http\Resources\ReferralLinkResourceCollection;
use App\Models\ReferralLink;
use App\Services\ReferralLinkService;
use Illuminate\Http\Request;
use DB;

class ReferralLinkController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new ReferralLink();

        if(!empty($data['keyword']))
            $items = $items->where('name', 'LIKE', '%'.$data['keyword'].'%');

        if(!empty($data['lead_source_id']))
            $items = $items->where('lead_source_id', $data['lead_source_id']);

        if(!empty($data['event_id']))
            $items = $items->where('event_id', $data['event_id']);

        if(!empty($data['agency_id']))
            $items = $items->where('agency_id', $data['agency_id']);

        if(!empty($data['created_by']))
            $items = $items->where('created_by_user', $data['created_by']);

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new ReferralLinkResourceCollection($items);
    }

    public function view($id)
    {
        $item = ReferralLink::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new ReferralLinkResource($item);
    }

    public function store(ReferralLinkRequest $request, ReferralLinkService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return new ReferralLinkResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function update(ReferralLinkRequest $request, ReferralLinkService $service)
    {
        $request->validated();
        if($obj = $service->update($request, $request->id)){
            return new ReferralLinkResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function form($token){
        $item = ReferralLink::where('token', $token)->where(function($query){
                    $query->whereNull('last_date_of_validity')
                            ->orWhere('last_date_of_validity', '>=', DB::raw('CURDATE()'));
        })->first();
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new ReferralLinkResource($item);
    }
}
