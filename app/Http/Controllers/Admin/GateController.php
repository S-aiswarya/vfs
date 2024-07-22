<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\GateRequest;
use App\Models\Gate;
use App\Helpers\BladeHelper;
use App\Traits\ResourceTrait;
use Illuminate\Http\Request;


class GateController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Gate;
        $this->route = 'admin.gates';
        $this->views = 'admin.gates';

        $this->permissions = ['list'=>'gate_listing', 'create'=>'gate_adding', 'edit'=>'gate_editing', 'delete'=>'gate_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('gates.*','centers.name as center')->join('centers','centers.id', '=', 'gates.center_id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->addColumn('action_emails', function($obj) use($route){
                return '<a href="'.route($route.'.emails', [encrypt($obj->id)]).'" class="text-info webadmin-open-ajax-popup" title="Emails" ><i class="fas fa-envelope"></i></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status', 'action_emails']);
    }

    protected function getSearchSettings(){}

    public function store(GateRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Gate successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(GateRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Gate successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

  
}
