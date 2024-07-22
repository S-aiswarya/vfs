<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\StageTaskActionRequest;
use App\Traits\ResourceTrait;
use App\Models\StageTaskAction;
use App\Models\Stage;

class StageTaskActionController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new StageTaskAction;
        $this->route .= '.stages.task-actions';
        $this->views .= '.stages.task_actions';

        $this->permissions = ['list'=>'stage_listing', 'create'=>'stage_adding', 'edit'=>'stage_editing', 'delete'=>'stage_deleting'];
        $this->resourceConstruct();

    }

    public function index($stage_id)
    {
        if (request()->ajax()) {
            $collection = $this->getCollection();
            $collection->where('stage_id', $stage_id);
            return $this->setDTData($collection)->make(true);
        } else {
            $search_settings = $this->getSearchSettings();
            $stage = Stage::find($stage_id);
            return view($this->views . '.index')->with('stage', $stage)->with('search_settings', $search_settings);
        }
    }

    protected function getCollection() {
        return $this->model->select('stage_task_actions.*');
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
		return view($this->views . '.form')->with('obj', $this->model)->with('stage', $stage);
	}

    public function edit($id) {
        $id = decrypt($id);
        if($obj = $this->model->find($id)){
            $stage = Stage::find($obj->stage_id);
            if(!$stage)
                return $this->redirect('notfound');
            return view($this->views . '.form')->with('obj', $obj)->with('stage', $stage);
        } else {
            return $this->redirect('notfound');
        }
    }

    public function store(StageTaskActionRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Stage task action successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(StageTaskActionRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);
        $data = $request->all();
        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Stage task action successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}