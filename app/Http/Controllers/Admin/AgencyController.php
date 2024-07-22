<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\AgencyEmailRequest;
use App\Http\Requests\Admin\AgencyRequest;
use App\Traits\ResourceTrait;
use App\Models\Agency;
use App\Models\AgencyEmail;
use View;

class AgencyController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Agency;
        $this->route = 'admin.agencies';
        $this->views = 'admin.agencies';

        $this->permissions = ['list'=>'agency_listing', 'create'=>'agency_adding', 'edit'=>'agency_editing', 'delete'=>'agency_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'phone_number', 'email', 'status', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->addColumn('action_emails', function($obj) use($route){
                return '<a href="'.route($route.'.emails', [encrypt($obj->id)]).'" class="text-info webadmin-open-ajax-popup" title="Emails" ><i class="fas fa-envelope"></i></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status', 'action_emails']);
    }

    protected function getSearchSettings(){}

    public function store(AgencyRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Agency successfully saved!'));
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
                return response()->json($this->_renderEdit($obj, 'Agency successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function emails($id){
        $id = decrypt($id);
        $emails = AgencyEmail::where('agency_id', $id)->get();
        return View::make($this->views . '.emails', array('emails'=>$emails, 'id'=>$id));
    }

    public function emailStore(AgencyEmailRequest $request){
        $data = $request->all();
        $agency_id = decrypt($data['id']);
        $saved_emails = AgencyEmail::where('agency_id', $agency_id)->pluck('email')->toArray();
        if(count($saved_emails)){
            $emails_to_delete = array_diff($saved_emails, $data['email']);
            if(count($emails_to_delete))
                AgencyEmail::whereIn('email', $emails_to_delete)->where('agency_id', $agency_id)->forceDelete();
        }

        foreach($data['email'] as $key=>$email){
            if(!empty($email)){
                $label = ($data['label'][$key])?$data['label'][$key]:$email;
                AgencyEmail::updateOrCreate(['email'=>$email, 'agency_id'=>$agency_id], ['label'=>$label]);
            }
        }

        $html = $this->emails(encrypt($agency_id))->render();
        return response()->json(['title'=>'Emails', 'html' => $html, 'message' => 'Email addresses successfully saved!']);
    }

}
