<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\CheckInRequest;
use App\Models\Check_In_Type;
use App\Traits\ResourceTrait;
use App\Models\CheckIn;
use App\Models\Country;
use App\Models\City;
use App\Models\Location;
use App\Models\Center;
use App\Models\Gate;
use App\Helpers\BladeHelper;
use Illuminate\Http\Request;

class CheckInController extends Controller

{

     use ResourceTrait;
    public function __construct()
    {
        parent::__construct();

        $this->model = new CheckIn;
        $this->route = 'admin.checkins';
        $this->views = 'admin.check_ins';

        $this->permissions = ['list'=>'checkin_listing', 'create'=>'checkin_adding', 'edit'=>'checkin_editing', 'delete'=>'checkin_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name','phonenumber','entry_time','exit_time','created_at', 'updated_at');
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

    public function store(CheckInRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $data['flag'] = $this->uploadImage($request, 'flag', 'country/flags');
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Check_in successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(CheckInRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            $data['flag'] = $this->uploadImage($request, 'flag', 'Check/flags', $obj->flag);

            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Check_In successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
