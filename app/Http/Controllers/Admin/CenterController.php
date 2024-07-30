<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\CenterRequest;
use App\Models\Center;
use App\Helpers\BladeHelper;
use App\Traits\ResourceTrait;

use Illuminate\Http\Request;

class CenterController extends Controller
{
    
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Center;
        $this->route = 'admin.centers';
        $this->views = 'admin.centers';

        $this->permissions = ['list'=>'center_listing', 'create'=>'center_adding', 'edit'=>'center_editing', 'delete'=>'center_deleting'];
       

        $this->resourceConstruct();

    }
   
    
    protected function getCollection() {
        return $this->model->select('centers.*','locations.name as location')->join('locations','locations.id', '=', 'centers.location_id');
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

    public function store(CenterRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $data['flag'] = $this->uploadImage($request, 'flag', 'center/flags');
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Center successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(CenterRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            $data['flag'] = $this->uploadImage($request, 'flag', 'Location/flags', $obj->flag);

            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Center successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }
}
