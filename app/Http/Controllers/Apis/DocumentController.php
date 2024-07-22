<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequestRequest;
use App\Http\Requests\DocumentStoreRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Http\Requests\DocumentUploadRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentResourceCollection;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request){

        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Document();

        if(empty($data['lead_id']))
            return response()->json(['message' => 'Invalid Request'], 400);
        else{
            $items = $items->where('lead_id', $data['lead_id']);
        }

        if(!empty($data['show_requests']))
            $items = $items->whereNull('file');

        if(!empty($data['keyword'])){
            $items = $items->where('title', 'LIKE', '%'.$data['keyword'].'%');
        }

        if(!empty($data['status']))
            $items = $items->where('status', $data['status']);

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
        return new DocumentResourceCollection($items);
    }

    public function view($id)
    {
        $item = Document::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new DocumentResource($item);
    }

    public function store(DocumentStoreRequest $request, DocumentService $service){
        $request->validated();
        return $service->store($request);
    }

    public function update(DocumentUpdateRequest $request, DocumentService $service){
        $request->validated();
        return $service->update($request);
    }

    public function request(DocumentRequestRequest $request, DocumentService $service){
        $request->validated();
        return $service->request($request);
    }

    public function upload(DocumentUploadRequest $request, DocumentService $service){
        $request->validated();
        return $service->upload($request, $this->getApiGuard());
    }

    public function accept(Request $request, DocumentService $service)
    {
        return $service->accept($request);
    }

    public function reject(Request $request, DocumentService $service)
    {
        return $service->reject($request);
    }
}
