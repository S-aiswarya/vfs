<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Helpers\BladeHelper;
use App\Http\Requests\CourseRequest;
use App\Models\SubjectArea;
use App\Services\CourseService;

class SubjectAreaController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new SubjectArea();
        $this->route = 'admin.subject-areas';
        $this->views = 'admin.subject_areas';

        $this->permissions = ['list'=>'subject_area_listing', 'create'=>'subject_area_adding', 'edit'=>'subject_area_editing', 'delete'=>'subject_area_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('subject_areas.*', 'course_levels.name as course_level')
                        ->join('course_levels', 'subject_areas.course_level_id', '=', 'course_levels.id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->editColumn('image', function($obj) use ($route) { 
                if($obj->image)
                    return '<a href="'.BladeHelper::asset($obj->image).'" target="_blank"><img src="'.BladeHelper::asset($obj->image).'" width="30px;"/></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'image', 'status']);
    }

    protected function getSearchSettings(){}

    public function store(CourseRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Subject Area successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(CourseRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);
        $data = $request->all();
        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Subject area successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
