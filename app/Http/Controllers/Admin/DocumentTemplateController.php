<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\DocumentTemplateRequest;
use App\Traits\ResourceTrait;
use App\Models\DocumentTemplate;
use App\Services\DocumentTemplateService;

class DocumentTemplateController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new DocumentTemplate;
        $this->route = 'admin.document-templates';
        $this->views = 'admin.document_templates';
        $this->permissions = ['list'=>'document_template_listing', 'create'=>'document_template_adding', 'edit'=>'document_template_editing', 'delete'=>'document_template_deleting'];
        $this->resourceConstruct();
    }
    
    protected function getCollection() {
        return $this->model->select('id', 'name', 'type', 'status', 'is_mandatory', 'created_at', 'updated_at');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
        ->editColumn('is_mandatory', function($obj) use($route) {
            if($obj->type == "Student"){
                if($obj->is_mandatory == 1)
                {
                    if(auth()->user()->can($this->permissions['edit'])){
                        return '<a href="' . route($route.'.make-mandatory', [encrypt($obj->id)]).'" class="webadmin-btn-warning-popup" data-message="Are you sure, want to make this template not mandatory?"><i class="h5 text-success fa fa-check-circle"></i></a>'; 
                    }
                    else
                        return '<i class="h5 text-success fa fa-check-circle"></i>';
                }
                else{
                    if(auth()->user()->can($this->permissions['edit']))
                        return '<a href="' . route($route.'.make-mandatory', [encrypt($obj->id)]) . '" class="webadmin-btn-warning-popup" data-message="Are you sure, want to make this template mandatory?"><i class="h5 text-danger fa fa-times-circle"></i></a>';
                    else
                        return '<i class="h5 text-danger fa fa-times-circle"></i>';
                }
            }
            else
                return "";
        })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status', 'is_mandatory']);
    }

    protected function getSearchSettings(){}

    public function store(DocumentTemplateRequest $request, DocumentTemplateService $service)
    {
        $request->validated();
        if($obj = $service->store($request->all())){
            return response()->json($this->_renderEdit($obj, 'Document template successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(DocumentTemplateRequest $request, DocumentTemplateService $service)
    {
        $request->validated();
        if($obj = $service->update($request->all())){
            return response()->json($this->_renderEdit($obj, 'Document template successfully updated!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function makeMandatory($id)
    {
        $id = decrypt($id);
        $obj = $this->model->find($id);
        if ($obj) {
            $status = $obj->is_mandatory;
            $set_status = ($status == 1)?0:1;
            $obj->is_mandatory = $set_status;
            $obj->save();
            $message = ($status == 1)?"made not mandatory":"made mandatory";
            return $this->redirect($message,'success', 'index');
        }
        return $this->redirect('notfound');
    }

}
