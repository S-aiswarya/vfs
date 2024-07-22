<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\StageRequest;
use App\Traits\ResourceTrait;
use App\Models\Stage;
use App\Models\SubStage;
use App\Services\StageService;
use Illuminate\Http\Request;

class StageController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Stage;
        $this->route = 'admin.stages';
        $this->views = 'admin.stages';
        $this->permissions = ['list'=>'stage_listing', 'create'=>'stage_adding', 'edit'=>'stage_editing', 'delete'=>'stage_deleting'];
        $this->resourceConstruct();
    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'processing_order', 'has_system_settings', 'type', 'status', 'created_at', 'updated_at')->where('parent_id', 0);
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->addColumn('substages', function($obj) use ($route) {
                $child_count  = Stage::where('parent_id', '=', $obj->id)->count();
                return '<a href="' . route( 'admin.substages.index',  [$obj->id] ) . '" class="btn btn-info btn-sm" >Substages ('.$child_count.')</a>'; 
            })
            ->addColumn('actions', function($obj){
                $html = '<div class="dropdown"><a href="javascript:void(0);" id="dropdownMenu'.$obj->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cog"></i></a><div class="dropdown-menu" aria-labelledby="dropdownMenu'.$obj->id.'">
                <a href="'.route('admin.stages.email-actions.index', [$obj->id]).'" target="_blank" class="dropdown-item">Set Emails</a>
                <a href="'.route('admin.stages.task-actions.index', [$obj->id]).'" target="_blank" class="dropdown-item">Set Tasks</a></div></div>';
                return $html;
            })
            ->addColumn('action_delete', function($obj) use ($route) {
                if(auth()->user()->can($this->permissions['delete']) && $obj->has_system_settings == 0)
                    return '<a href="'.route($route.'.destroy', [encrypt($obj->id)]).'" class="text-danger webadmin-btn-warning-popup" data-message="Are you sure to delete?  Associated data will be removed if it is deleted." title="' . ($obj->updated_at ? 'Last updated at : ' . date('d/m/Y - h:i a', strtotime($obj->updated_at)) : '') . '"><i class="fa fa-trash"></i></a>';
                else
                    return '<a href="javascript:void(0)" class="text-secondary" title="You have no permission to delete" ><i class="fa fa-trash"></i></a>';
            })
            ->addColumn('action_ajax_next_stages', function($obj) use ($route) {
                if(auth()->user()->can($this->permissions['edit']))
                    return '<a href="'.route($route.'.next-stages', [$obj->id]).'" class="text-warning webadmin-open-ajax-popup" title="Configure possible next stages of '.$obj->name.'" ><i class="fas fa-wrench"></i></a>';
                else
                    return '';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status', 'substages', 'actions', 'action_ajax_next_stages']);
    }

    protected function getSearchSettings(){}

    public function create()
	{   
		return view($this->views . '.form')->with('obj', $this->model)->with('action_types', $this->getStageTypes());
	}

    public function edit($id) {
        $id = decrypt($id);
        if($obj = $this->model->find($id)){
            return view($this->views . '.form')->with('obj', $obj)->with('action_types', $this->getStageTypes());
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

    public function nextStages($id){
        $current_stage = Stage::find($id);
        $stages = Stage::where('type', $current_stage->type)->where('id', '!=', $id)->get();
        $next_possible_stages = $current_stage->nextPossibleStages->pluck('id')->toArray();
        return view($this->views . '.next_stages')->with('stages', $stages)->with('id', $id)->with('next_possible_stages', $next_possible_stages);
    }

    public function nextStageStore(Request $request){
        $id = $request->id;
        $stage = Stage::find($id);
        if($stage){
            $next_stages_array = [];
            if($request->stage_id)
                foreach($request->stage_id as $key=>$next_stage){
                    if(!empty($next_stage))
                        $next_stages_array[$next_stage] = ['created_by'=>auth()->user()->id, 'updated_by'=>auth()->user()->id, 'created_at'=>date('Y-m-d H:i:s')];
                }
            $stage->nextPossibleStages()->sync($next_stages_array);
            $html = $this->nextStages($stage->id)->render();
            return response()->json(['title'=>'Configure possible next stages of '.$stage->name, 'html' => $html, 'message' => 'Next possible stages successfully configured!']);
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
