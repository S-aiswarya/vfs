<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Models\Intake;
use App\Http\Requests\Admin\IntakeRequest;
use App\Models\University;
use DB;

class IntakeController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Intake;
        $this->route = 'admin.intakes';
        $this->views = 'admin.intakes';

        $this->permissions = ['list'=>'intake_listing', 'create'=>'intake_adding', 'edit'=>'intake_editing', 'delete'=>'intake_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('intakes.*');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->editColumn('is_default', function ($obj) use ($route) {
                if ($obj->is_default == 1) {
                    return '<i class="h5 text-success fa fa-check-circle"></i>';  
                } else {
                    if (auth()->user()->can($this->permissions['edit']))
                        return '<a href="' . route($route . '.make-default', [encrypt($obj->id)]) . '" class="webadmin-btn-warning-popup" data-message="Are you sure, want to make this intake default?"><i class="h5 text-danger fa fa-times-circle"></i></a>';
                    else
                        return '<i class="h5 text-danger fa fa-times-circle"></i>';
                }
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status', 'is_default']);
    }

    protected function getSearchSettings(){}

    public function store(IntakeRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $check_first = DB::table('intakes')->where('is_default', 1)->count();
        if($check_first == 0)
            $data['is_default'] = 1;
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Intake successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(IntakeRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();
        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Intake successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function makeDefault($id)
    {
        $id = decrypt($id);
        $obj = $this->model->find($id);
        if ($obj) {
            DB::table('intakes')->update(['is_default'=>0]);
            $obj->is_default = 1;
            $obj->save();
            $message = "made default";
            return $this->redirect($message, 'success', 'index');
        }
        return $this->redirect('notfound');
    }

}
