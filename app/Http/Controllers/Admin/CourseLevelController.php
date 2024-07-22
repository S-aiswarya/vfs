<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\CourseLevelRequest;
use App\Traits\ResourceTrait;
use App\Models\CourseLevel;

class CourseLevelController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new CourseLevel;
        $this->route = 'admin.course-levels';
        $this->views = 'admin.course_levels';

        $this->permissions = ['list'=>'course_level_listing', 'create'=>'course_level_adding', 'edit'=>'course_level_editing', 'delete'=>'course_level_deleting'];

        $this->resourceConstruct();

    }
    
    protected function getCollection() {
        return $this->model->select('course_levels.*');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete']);
    }

    protected function getSearchSettings(){}

    public function store(CourseLevelRequest $request)
    {
        $request->validated();
        $data = $request->all();
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Course level successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(CourseLevelRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);
        $data = $request->all();
        if($obj = $this->model->find($id)){
            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Course level successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

}
