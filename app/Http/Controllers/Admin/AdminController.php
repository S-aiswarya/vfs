<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Traits\ResourceTrait;
use App\Models\Admin;
use App\Models\Role;
use DB;

class AdminController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Admin;
        $this->route = 'admin.admins';
        $this->views = 'admin.admins';

        $this->permissions = ['list'=>'admin_listing', 'create'=>'admin_adding', 'edit'=>'admin_editing', 'delete'=>'admin_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'email', 'status', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status']);
    }

    protected function getSearchSettings(){}

    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view($this->views . '.form')->with('obj', $this->model)->with('roles', $roles);
    }

    public function edit($id) {
        $id =  decrypt($id);
        if($obj = $this->model->find($id)){
            $roles = Role::where('guard_name', 'admin')->get();
            return view($this->views . '.form')->with('obj', $obj)->with('roles', $roles);
        } else {
            return $this->redirect('notfound');
        }
    }
    public function store(AdminRequest $request)
    {
        $request->validated();
        $data = $request->all();

        $this->model->fill($data);
        if($this->model->save())
        {
            $this->assignRole($request->input('role'), $this->model);
            return response()->json($this->_renderEdit($this->model, 'System Admin successfully saved.'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(AdminRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $id = decrypt($data['id']);

        if($obj = $this->model->find($id)){
            if($obj->update($data))
            {
                DB::table('model_has_roles')->where('model_type', Admin::class)->where('model_id',$id)->delete();
                $this->assignRole($request->input('role'), $obj);
                return response()->json($this->_renderEdit($obj, 'System Admin successfully updated.'));
            }
            return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
        } else {
            return $this->redirect('notfound');
        }
    }

    protected function assignRole($role, $obj){
        $data = ['role_id' => $role, 'model_type' => Admin::class, 'model_id' => $obj->id];
        DB::table('model_has_roles')->insert($data);
    }

}
