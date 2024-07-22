<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Models\Stage;
use App\Models\SubStage;
use App\Http\Requests\StageRequest;
use App\Services\StageService;

class SubStageController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Stage;
        $this->route .= '.substages';
        $this->views .= '.substages';

        $this->permissions = ['list'=>'stage_listing', 'create'=>'stage_adding', 'edit'=>'stage_editing', 'delete'=>'stage_deleting'];
        $this->resourceConstruct();

    }

    public function index($stage_id)
    {
        if (request()->ajax()) {
            $collection = $this->getCollection();
            $collection->where('parent_id', $stage_id);
            return $this->setDTData($collection)->make(true);
        } else {
            $search_settings = $this->getSearchSettings();
            $stage = Stage::find($stage_id);
            return view($this->views . '.index')->with('stage', $stage)->with('search_settings', $search_settings);
        }
    }

    protected function getCollection() {
        return $this->model->select('stages.*');
    }

    protected function setDTData($collection) {
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status']);
    }

    protected function getSearchSettings(){}

    public function create($stage_id)
	{   $stage = Stage::find($stage_id);
        if(!$stage)
            return $this->redirect('notfound');
		return view($this->views . '.form')->with('obj', $this->model)->with('stage', $stage)->with('action_types', $this->getStageTypes());
	}

    public function edit($id) {
        $id = decrypt($id);
        if($obj = $this->model->find($id)){
            $stage = Stage::find($obj->parent_id);
            if(!$stage)
                return $this->redirect('notfound');
            return view($this->views . '.form')->with('obj', $obj)->with('stage', $stage)->with('action_types', $this->getStageTypes());
        } else {
            return $this->redirect('notfound');
        }
    }

    public function store(StageRequest $request, StageService $service)
    {
        $request->validated();
        if($obj = $service->store($request->all())){
            return response()->json($this->_renderEdit($obj, 'Stage successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(StageRequest $request, StageService $service)
    {
        $request->validated();
        if($obj = $service->update($request->all())){
            return response()->json($this->_renderEdit($obj, 'Stage successfully updated!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}