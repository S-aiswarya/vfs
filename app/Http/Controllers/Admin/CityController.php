<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Http\Requests\Admin\CityRequest;
use App\models\City;
use App\Helpers\BladeHelper;
use Illuminate\Http\Request;

class CityController extends Controller
{
    
        use ResourceTrait;
    
        public function __construct()
        {
            parent::__construct();
    
            $this->model = new City;
            $this->route = 'admin.cities';
            $this->views = 'admin.cities';
    
            $this->permissions = ['list'=>'city_listing', 'create'=>'city_adding', 'edit'=>'city_editing', 'delete'=>'city_deleting'];
    
            $this->resourceConstruct();
    
        }
       
        
        protected function getCollection() {
            return $this->model->select('cities.*','office_countries.name as country')->join('office_countries','office_countries.id', '=', 'cities.country_id');
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
    
        public function store(CityRequest $request)
        {
            $request->validated();
            $data = $request->all();
            $data['flag'] = $this->uploadImage($request, 'flag', 'country/flags');
            if($obj = $this->_store($data)){
                return response()->json($this->_renderEdit($obj, 'City successfully saved!'));
            }
            return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
        }
    
        public function update(CityRequest $request)
        {
            $request->validated();
            $id = decrypt($request->id);
    
            $data = $request->all();
    
            if($obj = $this->model->find($id)){
                $data['flag'] = $this->uploadImage($request, 'flag', 'City/flags', $obj->flag);
    
                if($obj = $this->_update($obj, $data)){
                    return response()->json($this->_renderEdit($obj, 'City successfully updated!'));
                }
            }
            return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
        }
}

