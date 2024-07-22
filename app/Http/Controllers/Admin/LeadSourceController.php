<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Models\LeadSource;
use App\Http\Requests\Admin\LeadSourceRequest;

class LeadSourceController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new LeadSource;
        $this->route = 'admin.lead-sources';
        $this->views = 'admin.lead_sources';

        $this->permissions = ['list'=>'lead_source_listing', 'create'=>'lead_source_adding', 'edit'=>'lead_source_editing', 'delete'=>'lead_source_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('lead_sources.*');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->addColumn('action_delete', function($obj) use ($route) {
                if(auth()->user()->can($this->permissions['delete']) && $obj->has_system_settings == 0)
                    return '<a href="'.route($route.'.destroy', [encrypt($obj->id)]).'" class="text-danger webadmin-btn-warning-popup" data-message="Are you sure to delete?  Associated data will be removed if it is deleted." title="' . ($obj->updated_at ? 'Last updated at : ' . date('d/m/Y - h:i a', strtotime($obj->updated_at)) : '') . '"><i class="fa fa-trash"></i></a>';
                else
                    return '<a href="javascript:void(0)" class="text-secondary" title="You have no permission to delete" ><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete']);
    }

    protected function getSearchSettings(){}

    public function store(LeadSourceRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Lead source successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(LeadSourceRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);
        $data = $request->all();
        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Lead source successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
