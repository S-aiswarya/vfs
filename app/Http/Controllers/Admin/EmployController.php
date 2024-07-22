<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\EmployRequest;
use App\Traits\ResourceTrait;
use App\Models\Employ;
use App\Helpers\BladeHelper;
use Illuminate\Http\Request;

class EmployController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Employ;
        $this->route = 'admin.employees';
        $this->views = 'admin.employees';

        $this->permissions = ['list'=>'employees_listing', 'create'=>'employees_adding', 'edit'=>'employees_editing', 'delete'=>'employees_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'employ_name', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->editColumn('flag', function($obj) use ($route) { 
                if($obj->flag)
                    return '<a href="'.BladeHelper::asset($obj->flag).'" target="_blank"><img src="'.BladeHelper::asset($obj->flag).'" width="30px;"/></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'flag']);
    }

    protected function getSearchSettings(){}

    public function store(EmployRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $data['flag'] = $this->uploadImage($request, 'flag', 'country/flags');
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Employ successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(EmployRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            $data['flag'] = $this->uploadImage($request, 'flag', 'country/flags', $obj->flag);

            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Employ successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }
}
