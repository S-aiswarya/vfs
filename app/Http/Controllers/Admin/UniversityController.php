<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Models\University;
use App\Helpers\BladeHelper;
use App\Http\Requests\Admin\UniversityEmailRequest;
use App\Http\Requests\Admin\UniversityRequest;
use App\Models\UniversityContact;
use View;

class UniversityController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new University;
        $this->route = 'admin.universities';
        $this->views = 'admin.universities';

        $this->permissions = ['list'=>'university_listing', 'create'=>'university_adding', 'edit'=>'university_editing', 'delete'=>'university_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('universities.*', 'countries.name as country')->join('countries', 'countries.id', '=', 'universities.country_id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->editColumn('logo', function($obj) use ($route) { 
                if($obj->logo)
                    return '<a href="'.BladeHelper::asset($obj->logo).'" target="_blank"><img src="'.BladeHelper::asset($obj->logo).'" width="30px;"/></a>';
            })
            ->addColumn('action_emails', function($obj) use($route){
                return '<a href="'.route($route.'.emails', [encrypt($obj->id)]).'" class="text-info webadmin-open-ajax-popup" title="Emails" ><i class="fas fa-envelope"></i></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'logo', 'status', 'action_emails']);
    }

    protected function getSearchSettings(){}

    public function store(UniversityRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $data['logo'] = $this->uploadImage($request, 'logo', 'universities/logo');
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'University successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(UniversityRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            $data['logo'] = $this->uploadImage($request, 'logo', 'universities/logo', $obj->logo);

            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'University successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function emails($id){
        $id = decrypt($id);
        $emails = UniversityContact::where('university_id', $id)->get();
        return View::make($this->views . '.emails', array('emails'=>$emails, 'id'=>$id));
    }

    public function emailStore(UniversityEmailRequest $request){
        $data = $request->all();
        $university_id = decrypt($data['id']);
        $saved_emails = UniversityContact::where('university_id', $university_id)->pluck('email')->toArray();
        if(count($saved_emails)){
            $emails_to_delete = array_diff($saved_emails, $data['email']);
            if(count($emails_to_delete))
            UniversityContact::whereIn('email', $emails_to_delete)->where('university_id', $university_id)->forceDelete();
        }

        foreach($data['email'] as $key=>$email){
            if(!empty($email)){
                $label = ($data['label'][$key])?$data['label'][$key]:$email;
                $check_exist = UniversityContact::where('label', $label)->where('university_id', $university_id)->first();
                if(!$check_exist){
                    $check_exist = new UniversityContact();
                    $check_exist->label = $label;
                    $check_exist->university_id = $university_id;
                }
                $check_exist->email = $email;
                $check_exist->phone_number = $data['phone_number'][$key];
                $check_exist->save();
            }
        }

        $html = $this->emails(encrypt($university_id))->render();
        return response()->json(['title'=>'Emails', 'html' => $html, 'message' => 'University contacts successfully saved!']);
    }

}
