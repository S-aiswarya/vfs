<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\AgencyRequest;
use App\Traits\ResourceTrait;
use App\Models\ReferralList;

class ReferralTypeController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new ReferralList();
        $this->route = 'admin.referral-types';
        $this->views = 'admin.referral_types';

        $this->permissions = ['list'=>'referral_type_listing', 'create'=>'referral_type_adding', 'edit'=>'referral_type_editing', 'delete'=>'referral_type_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'status', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status']);
    }

    protected function getSearchSettings(){}

    public function store(AgencyRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Referral successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(AgencyRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Referral successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
