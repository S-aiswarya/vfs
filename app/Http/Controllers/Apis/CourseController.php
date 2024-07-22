<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\Apis\CourseResource;
use App\Http\Resources\Apis\CourseResourceCollection;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request){
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Course();

        if(!empty($data['course_level_id']))
            $items = $items->where('course_level_id', $data['course_level_id']);

        if(!empty($data['created_by']))
            $items = $items->where('created_by_user', $data['created_by']);

        $order_field = 'updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new CourseResourceCollection($items);
    }

    public function view($id)
    {
        $item = Course::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new CourseResource($item);
    }

    public function store(CourseRequest $request, CourseService $service)
    {
        $request->validated();
        if($obj = $service->store($request)){
            return new CourseResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }

    public function update(CourseRequest $request, CourseService $service)
    {
        $request->validated();
        if($obj = $service->update($request, $request->id)){
            return new CourseResource($obj);
        }
        return response()->json(['message' => 'Invalid Request'], 400);
    }
}
