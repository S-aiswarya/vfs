<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\GuardRequest;
use App\Models\Guard;
use App\Helpers\BladeHelper;
use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
class GuardController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Guard;
        $this->route = 'admin.guards';
        $this->views = 'admin.guards';

        $this->permissions = ['list'=>'guard_listing', 'create'=>'guard_adding', 'edit'=>'guard_editing', 'delete'=>'guard_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection(){ 
        return $this->model->select('guard_sign_in.*','users.name as user')->join('users','users.id','=','guard_sign_in.user_id')->join('centers.name as center','=','guard_sign_in.center_id');
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

    public function store(GuardRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Guard successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(GuardRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Guard successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

  
}
