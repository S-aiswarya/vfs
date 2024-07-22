<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommunicationLogResource;
use App\Http\Resources\CommunicationLogResourceCollection;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use DB;

class CommunicationLogController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new CommunicationLog();

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
            $items = $items->whereIn('type', $data['type']);

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
        $item = CommunicationLog::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new CommunicationLogResource($item);
    }

    public function summary(Request $request){
        $data = $request->all();
        $communication_log = new CommunicationLog();

        if(!empty($data['lead_id']))
            $communication_log = $communication_log->where('lead_id', $data['lead_id']);

        if(!empty($data['application_id']))
            $communication_log = $communication_log->where('application_id', $data['application_id']);
        $email_send_summary = clone $communication_log;
        $email_receive_summary = clone $communication_log;
        $whatsapp_send_summary = clone $communication_log;
        $whatsapp_receive_summary = clone $communication_log;

        $output = [];

        $output['email_send_summary'] = $email_send_summary->where('type', 'Send')->count();
        $output['email_receive_summary'] = $email_receive_summary->where('type', 'Gmail')->count();
        $output['whatsapp_send_summary'] = $whatsapp_send_summary->where('type', 'Whatsapp Send')->count();
        $output['whatsapp_receive_summary'] = $whatsapp_receive_summary->where('type', 'Whatsapp')->count();
        return response()->json(['data' => $output]);
    }

}
