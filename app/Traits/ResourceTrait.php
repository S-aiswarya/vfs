<?php 

namespace App\Traits;

use View, Request, DataTables, Form, Redirect, Artisan;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request as HttpRequest;

trait ResourceTrait {

	protected $model, $entity, $permissions;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function resourceConstruct()
	{
        $this->middleware('permission:'.$this->permissions['list'].'|'.$this->permissions['create'].'|'.$this->permissions['edit'].'|'.$this->permissions['delete'], ['only' => ['index']]);
        $this->middleware('permission:'.$this->permissions['create'], ['only' => ['create','store']]);
        $this->middleware('permission:'.$this->permissions['edit'], ['only' => ['edit','update']]);
        $this->middleware('permission:'.$this->permissions['delete'], ['only' => ['destroy']]);

		$this->entity = $this->getEntityName();

		View::share(['route' => $this->route, 'views' => $this->views, 'entity' => $this->entity, 'permissions'=>$this->permissions]);
	}

	protected function getEntityName() {
		$name = class_basename($this->model);
        $parts = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
        return ucfirst(strtolower(implode(' ', $parts)));
	}

	/**
	 * Show the data list.
	 *
	 * @return Response
	 */
	public function index()
	{
        if (Request::ajax()) {
            $collection = $this->getCollection();
            return $this->setDTData($collection)->make(true);
        } else {
            $search_settings = $this->getSearchSettings();
			return view($this->views . '.index')->with('search_settings', $search_settings);
        }
	}

	abstract protected function getCollection();

    abstract protected function getSearchSettings();

	protected function initDTData($collection, $queries = []) {
		$route = $this->route;
		return Datatables::of($collection)
            ->setRowId('row-{{ $id }}')
            ->addColumn('date', function($obj){
                if($obj->updated_at)
                    return date('d M, Y h:i A', strtotime($obj->updated_at));
                else
                    return '';
            })
            ->orderColumn('date', function ($query, $order) {
                     $query->orderBy('updated_at', $order);
            })
            ->editColumn('status', function($obj) use($route) {
                if(Route::has($route.'.change-status')){
                    if($obj->status == 1)
                    {
                        if(auth()->user()->can($this->permissions['edit']))
                            return '<a href="' . route($route.'.change-status', [encrypt($obj->id)]).'" class="webadmin-btn-warning-popup" data-message="Are you sure, want to disable this record?"><i class="h5 text-success fa fa-check-circle"></i></a>'; 
                        else
                            return '<i class="h5 text-success fa fa-check-circle"></i>';
                    }
                    else{
                        if(auth()->user()->can($this->permissions['edit']))
                            return '<a href="' . route($route.'.change-status', [encrypt($obj->id)]) . '" class="webadmin-btn-warning-popup" data-message="Are you sure, want to enable this record?"><i class="h5 text-danger fa fa-times-circle"></i></a>';
                        else
                            return '<i class="h5 text-danger fa fa-times-circle"></i>';
                    }
                }
                return '';
            })
            ->addColumn('action_edit', function($obj) use ($route, $queries) {
                if(Route::has($route.'.edit') || Route::has($route.'.show')){ 
                    if(auth()->user()->can($this->permissions['edit']))
                        return '<a href="'.route($route.'.edit', [encrypt($obj->id)]).'" class="text-info" title="' . ($obj->updated_at ? 'Last updated at : ' . date('d/m/Y - h:i a', strtotime($obj->updated_at)) : ''). '" ><i class="fa fa-pencil-alt"></i></a>';
                    else
                        return '<a href="'.route($route.'.show', [encrypt($obj->id)]).'" class="text-info webadmin-open-ajax-popup" data-popup-size="large" title="View '.$obj->name.'" ><i class="fas fa-eye"></i></a>';
                }
                return '';
            })
            ->addColumn('action_ajax_edit', function($obj) use ($route) {
                if(Route::has($route.'.edit') || Route::has($route.'.show')){ 
                    if(auth()->user()->can($this->permissions['edit']))
                        return '<a href="'.route($route.'.edit', [encrypt($obj->id)]).'" class="text-info webadmin-open-ajax-popup" title="Edit '.$obj->name.'" ><i class="fa fa-pencil-alt"></i></a>';
                    else
                        return '<a href="'.route($route.'.show', [encrypt($obj->id)]).'" class="text-info webadmin-open-ajax-popup" title="' . $obj->name. '" ><i class="fas fa-eye"></i></a>';
                }
                return '';
            })
            ->addColumn('action_delete', function($obj) use ($route, $queries) {
                if(Route::has($route.'.destroy')){
                    if(auth()->user()->can($this->permissions['delete']))
                        return '<a href="'.route($route.'.destroy', [encrypt($obj->id)]).'" class="text-danger webadmin-btn-warning-popup" data-message="Are you sure to delete?  Associated data will be removed if it is deleted." title="' . ($obj->updated_at ? 'Last updated at : ' . date('d/m/Y - h:i a', strtotime($obj->updated_at)) : '') . '"><i class="fa fa-trash"></i></a>';
                    else
                        return '<a href="javascript:void(0)" class="text-secondary" title="You have no permission to delete" ><i class="fa fa-trash"></i></a>';
                }
                return '';
            });
	}

	protected function setDTData($collection) {
		return $this->initDTData($collection);
	}

	/**
	 * Show the add form.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view($this->views . '.form')->with('obj', $this->model);
	}

	public function show($id)
	{
		$id = decrypt($id);
        if($obj = $this->model->find($id)){
            return view($this->views . '.view')->with('obj', $obj);
        } else {
            return $this->redirect('notfound');
        }
	}

    public function store(HttpRequest $request)
    {
        $data = $request->all();
    	$this->model->validate($data);
        return $this->_store($data);
    }

	protected function _store($data)
	{
       
        $data['is_featured'] = isset($data['is_featured'])?1:0;
        $data['priority'] = (!empty($data['priority']))?$data['priority']:0;
        
		$this->model->fill($data);
        if($this->model->save())
            return $this->model;
        else
            return false;
	}

    public function edit($id) {
        $id = decrypt($id);
        if($obj = $this->model->find($id)){
            return view($this->views . '.form')->with('obj', $obj);
        } else {
            return $this->redirect('notfound');
        }
    }

    public function update(HttpRequest $request) {
        $data = $request->all();
        $id = decrypt($data['id']);
    	$this->model->validate($data, $id);
        return $this->_update($id, $data);
    }

    protected function _file_upload($file, $file_path){
        $path = null;
        if($file->isValid())
        {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $file->storeAs($file_path, $fileNameToStore, ['disk' => 'public']);
            $path = 'uploads/'.$path;
        }
        return $path;
    }

    protected function _update($obj, $data) {
        if($obj){
            $data['is_featured'] = isset($data['is_featured'])?1:0;
            $data['priority'] = (!empty($data['priority']))?$data['priority']:0;
        	if($obj->update($data))
                return $obj;
        }
        return false;
    }

    public function changeStatus($id)
    {
        $id = decrypt($id);
        $obj = $this->model->find($id);
        if ($obj) {
            $status = $obj->status;
            $set_status = ($status == 1)?0:1;
            $obj->status = $set_status;
            $obj->save();
            $message = ($status == 1)?"disabled":"enabled";
            return $this->redirect($message,'success', 'index');
        }
        return $this->redirect('notfound');
    }
    
    public function destroy($id) {
        $id = decrypt($id);
        $obj = $this->model->find($id);
        if ($obj) {
            $obj->delete();
            return $this->redirect('removed','success', 'index');
        }
        
        return $this->redirect('notfound');
    }


    /**
     * Redirect after an operation
     * @return Redirect redirect object
     */
	protected function redirect($op = null, $type = 'success', $view = 'edit', $params='')
	{
        if($type == 'success')
        {
            $message = '';
            
            if($op =='created')
                $message = 'created';
            elseif($op =='removed')
                $message = 'deleted';
            elseif($op =='disabled')
                $message = 'disabled';
            elseif($op =='enabled')
                $message = 'enabled';
            elseif($op == 'updated')
                $message = 'updated';
            else
                $message = $op;

            if (Request::ajax())
                $response = response()->json(['success'=>$this->entity.' successfully '.$message.'!']);
            else
                $response = Redirect::route($this->route . '.' . $view, $params)->withSuccess($this->entity.' successfully '.$message.'!');
        }
        else
            if (Request::ajax())
                $response = response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
            else
                $response = Redirect::back()->withInput();

        return $response;
	}

    protected function applyFiltering($collection)
    {
        $search = request()->get('data');
        if($search)
        {
            foreach ($search as $key => $value) {
                $condition = null;
                $keyArr =  explode('-', $key);
                if(isset($keyArr[1]))
                {
                        $key = $keyArr[1];
                        $condition = $keyArr[0];
                 }
                if($value)
                {
                    if($condition == 'date_between')
                    {
                            $date_array = explode('-', $value);
                            $from_date = $this->formatDate($date_array[0]);
                            $from_date = date('Y-m-d H:i:s', strtotime($from_date.' 00:00:00'));
                            $to_date = $this->formatDate($date_array[1]);
                            $to_date = date('Y-m-d H:i:s', strtotime($to_date.' 23:55:55'));
                            $collection->whereBetween($key, [$from_date, $to_date]);
                    }
                    elseif($condition == 'like')
                    {
                        $collection->where($key, 'like', '%' . $value . '%');
                    }
                    else
                        $collection->where($key,$value);
                }
            }
        }
            
        return $collection;
    }

    function formatDate($date)
    {
        return implode("-", explode("/", $date));
    }

    protected function parse_date_time($input)
    {
        $output = null;
        if($input)
        {
            $arrStartRange = explode(' ', trim($input));
            $arrDate = explode("/", $arrStartRange[0]);
            $arrTime = explode(":", $arrStartRange[1]);
            $output = date('Y-m-d H:i:s', strtotime($arrDate[2].'-'.$arrDate[1].'-'.$arrDate[0].' '.$arrTime[0].':'.$arrTime[1].':00'));
        }
        return $output;
    }

    public function clear_cache()
    {
        cache()->flush();
        //Artisan::call('config:cache');
        //Artisan::call('cache:clear');
        return true;
    }

}