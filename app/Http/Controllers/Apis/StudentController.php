<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentCreateRequest;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentResourceCollection;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        $limit = (isset($data['limit']) && trim($data['limit']) != '')?(int)$data['limit']:10;
        $items = new Student();

        if(!empty($data['keyword']) || !empty($data['name']) || !empty($data['email']) || !empty($data['phone_number']))
        {
            $items = $items->whereHas('user', function($user) use($data){
                $user->where('users.status', 1)
                    ->where(function($query2) use($data){
                        if(!empty($data['keyword'])){
                            $query2->where('name', 'LIKE', '%'.$data['keyword'].'%')
                                ->orWhere('email', 'LIKE', '%'.$data['keyword'].'%')
                                ->orWhere('phone_number', 'LIKE', '%'.$data['keyword'].'%');
                        }
                        if(!empty($data['name'])){
                            $query2->where('name', 'LIKE', '%'.$data['keyword'].'%');
                        }
                        if(!empty($data['email'])){
                            $query2->where('email', $data['email']);
                        }
                        if(!empty($data['phone_number'])){
                            $query2->where('phone_number', $data['phone_number']);
                        }
                    });
            });
        }
        else{
            $items = $items->whereHas('user', function($user){
                $user->where('users.status', 1);
            });
        }

        if(!empty($data['created_by'])){
            $items = $items->where('students.created_by', $data['created_by']);
        }

        if(!empty($data['closed'])){
            $items = $items->where('students.closed', 1);
        }
        else{
            $items = $items->where('students.closed', 0);
        }

        $order_field = 'students.updated_at';
        $order_dir = 'DESC';
        if(isset($data['sort_field']) && trim($data['sort_field']) != "")
        {
            $order_field = $data['sort_field'];
            $order_dir = (isset($data['sort_order']) && trim($data['sort_order']) != "")?$data['sort_order']:'ASC';
        }

        $items = $items->orderBy($order_field, $order_dir)->paginate($limit);
        return new StudentResourceCollection($items);
    }

    public function create(StudentStoreRequest $request, StudentService $service)
    {
        $request->validated();
        return $service->store($request->all());
    }

    public function view($id)
    {
        $item = Student::find($id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        return new StudentResource($item);
    }

    public function update(StudentUpdateRequest $request, StudentService $service)
    {
        $request->validated();
        return $service->update($request->all());
    }

    public function close(Request $request, StudentService $service)
    {
        return $service->close($request);
    }
}
