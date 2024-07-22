<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;

use App\Models\Permission;

use Illuminate\Http\Request as Reqst;
use Request, View, Redirect, DB, Image, Validator;

class PermissionController extends Controller
{
    use ResourceTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = new Permission;

        $this->route .= '.permissions';
        $this->views .= '.permissions';

        $this->permissions = ['list'=>'permission_listing', 'create'=>'permission_adding', 'edit'=>'permission_editing', 'delete'=>'permission_deleting'];
        $this->resourceConstruct();

    }

    protected function getCollection() {
        return $this->model->select('id', 'name', 'route', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit']);
    }

    protected function getSearchSettings(){}

    public function show($id)
    {
        $this->edit($id);
    }

    public function edit($id) {
        $id = decrypt($id);
        if($obj = $this->model->find($id)){
            return view($this->views . '.edit')->with('obj', $obj);
        } else {
            return $this->redirect('notfound');
        }
    }


    public function store(Reqst $r)
    {
        $data = $r->all();

        $validator = Validator::make($data, [
            'name.*' => 'required|max:250',
            'route.*' => 'required|max:250',
        ]);

        if ($validator->fails()){
            return Redirect::back()->withInput()->withErrors($validator->errors()->all());
        }
        else
        {
            foreach ($data['name'] as $key => $name) {
                $input = [];
                $input['name'] = $name;
                $input['route'] = $data['route'][$key];
                $input['guard_name'] = $data['guard_name'][$key];
                $permissions = new Permission;
                $permissions->fill($input);
                $permissions->save();
            }
            $this->clear_cache();
            return Redirect::to(route($this->route. '.index'))->withSuccess('Permissions successfully saved!'); 
        } 
    }

    public function update(Reqst $r)
    {
        $data = $r->all();
        $id = decrypt($data['id']);
        $validator = Validator::make($data, [
            'name' => 'required|max:250',
            'route' => 'required|max:250',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>'Oops!! look like you have missed some important data, please check.']);
        }
        else
        {
            if($obj = $this->model->find($id)){
                
                $obj->name = $data['name'];
                $obj->route = $data['route'];
                $obj->guard_name = $data['guard_name'];
                $obj->save();
                $this->clear_cache();
                return response()->json(['success'=>'Permission details successfully updated']);
            } else {
                return response()->json(['error'=>'Oops!! something went wrong...Please try again.']);
            }
        }
    }

}
