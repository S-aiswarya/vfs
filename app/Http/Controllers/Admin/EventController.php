<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\EventRequest;
use App\Traits\ResourceTrait;
use App\Models\Event;
use App\Services\EventService;

class EventController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Event();
        $this->route = 'admin.events';
        $this->views = 'admin.events';

        $this->permissions = ['list'=>'event_listing', 'create'=>'event_adding', 'edit'=>'event_editing', 'delete'=>''];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'status', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status']);
    }

    protected function getSearchSettings(){}

    public function store(EventRequest $request, EventService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return response()->json($this->_renderEdit($obj, 'Event successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(EventRequest $request, EventService $service)
    {
        $request->validated();
        $id = decrypt($request->id);
        if($obj = $service->update($request, $id)){
            return response()->json($this->_renderEdit($obj, 'Event successfully updated!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
