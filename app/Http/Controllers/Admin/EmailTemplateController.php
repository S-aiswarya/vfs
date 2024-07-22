<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\EmailTemplateRequest;
use App\Traits\ResourceTrait;
use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;

class EmailTemplateController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new EmailTemplate;
        $this->route = 'admin.email-templates';
        $this->views = 'admin.email_templates';

        $this->permissions = ['list'=>'email_template_listing', 'create'=>'email_template_adding', 'edit'=>'email_template_editing', 'delete'=>'email_template_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'subject', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete']);
    }

    protected function getSearchSettings(){}

    public function store(EmailTemplateRequest $request, EmailTemplateService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return response()->json($this->_renderEdit($obj, 'Email template successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(EmailTemplateRequest $request, EmailTemplateService $service)
    {
        $request->validated();
        $id = decrypt($request->id);
        if($obj = $service->update($request, $id)){
            return response()->json($this->_renderEdit($obj, 'Email template successfully updated!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
