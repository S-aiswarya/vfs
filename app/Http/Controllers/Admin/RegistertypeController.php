<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\RegistertypeRequest;
use App\Helpers\BladeHelper;
use App\Models\Register_type;
use App\Traits\ResourceTrait;
use Illuminate\Http\Request;

class RegistertypeController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model =  new Register_type;
        $this->route = 'admin.register_types';
        $this->views = 'admin.register_types';

        $this->permissions = ['list'=>'register_type_listing', 'create'=>'register_type_adding', 'edit'=>'register_type_editing', 'delete'=>'register_type_deleting'];
       

        $this->resourceConstruct();

    }
   
    
    protected function getCollection() {
        return $this->model->select('register_types.*','key_types.key_name as key_types')->join('key_types','key_types.id', '=', 'register_types.key_id');
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

    public function store(RegistertypeRequest $request)
    {
        $request->validated();
        $data = $request->all();

        
        $data['flag'] = $this->uploadImage($request, 'flag', 'country/flags');
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Register successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(RegistertypeRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            $data['flag'] = $this->uploadImage($request, 'flag', 'Location/flags', $obj->flag);

            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Register successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }
}