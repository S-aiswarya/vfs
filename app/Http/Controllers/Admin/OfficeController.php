<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Models\Office;
use App\Http\Requests\Admin\OfficeRequest;
use App\Models\Role;

class OfficeController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Office;
        $this->route = 'admin.offices';
        $this->views = 'admin.offices';

        $this->permissions = ['list'=>'office_listing', 'create'=>'office_adding', 'edit'=>'office_editing', 'delete'=>'office_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('offices.*', 'office_countries.name as country')->join('office_countries', 'office_countries.id', '=', 'country.country_id');
    }

    protected function setDTData($collection) {
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status']);
    }

    protected function getSearchSettings(){}

    public function create()
    {
        $roles = Role::where('guard_name', 'user')->where('id', '!=', 3)->get();
        return view($this->views . '.form')->with('obj', $this->model)->with('roles', $roles);
    }

    public function edit($id) {
        $id =  decrypt($id);
        if($obj = $this->model->find($id)){
            $roles = Role::where('guard_name', 'user')->where('id', '!=', 3)->get();
            return view($this->views . '.form')->with('obj', $obj)->with('roles', $roles);
        } else {
            return $this->redirect('notfound');
        }
    }

    public function store(OfficeRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            if(!empty($data['user']))
                $obj->users()->attach($data['user']);
            return response()->json($this->_renderEdit($obj, 'Office successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(OfficeRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                $users = !empty($data['user'])?$data['user']:[];
                $obj->users()->sync($users);
                return response()->json($this->_renderEdit($obj, 'Office successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
