<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Imports\LeadImport;
use App\Traits\ResourceTrait;
use App\Models\Lead;
use App\Models\Stage;
use Illuminate\Http\Request;
use View;

class LeadController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Lead;
        $this->route = 'admin.leads';
        $this->views = 'admin.leads';
        $this->permissions = ['list'=>'lead_listing', 'create'=>'', 'edit'=>'', 'delete'=>''];
        $this->resourceConstruct();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $collection = $this->getCollection();
            if(request()->get('data'))
            {
                $collection = $this->applyFiltering($collection);
            }

            return $this->setDTData($collection)->make(true);
        } else {
            
            $search_settings = $this->getSearchSettings();
            return view::make($this->views . '.index', array('search_settings'=>$search_settings));
        }
    }
    
    protected function getCollection() {
        return $this->model->select('leads.*', 'offices.name as office', 'agencies.name as agency', 'stages.name as stage', 'users.name as assigned_to')
                ->leftJoin('stages', 'stages.id', '=', 'leads.stage_id')
                ->leftJoin('users', 'users.id', '=', 'leads.assign_to_user_id')
                ->leftJoin('offices', 'offices.id', '=', 'leads.assign_to_office_id')
                ->leftJoin('agencies', 'agencies.id', '=', 'leads.agency_id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->addColumn('verification_status', function($obj){
                if($obj->user_id)
                    return '<span class="badge bg-success">Verified</span>';
                else
                    return '<span class="badge bg-danger">Not Verified</span>';
            })
            ->addColumn('archive_status', function($obj){
                if($obj->closed == 1)
                    return '<span class="badge bg-danger">Archived</span>';
                else
                    return '<span class="badge bg-success">Open</span>';
            })
            ->rawColumns(['action_ajax_edit', 'verification_status', 'archive_status']);
    }

    protected function getSearchSettings(){
        $settings = [];
        $settings['stages'] = Stage::where('parent_id', 0)->where('type', 'Student')->get();
        return $settings;
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
                            $to_date = date('Y-m-d H:i:s', strtotime($to_date.' 00:00:00'));
                            $collection->whereBetween($key, [$from_date, $to_date]);
                    }
                    elseif($condition == 'like')
                    {
                        $collection->where(function($query) use($value){
                            $query->where('name', 'LIKE', '%'.$value.'%')
                                ->orWhere('email', 'LIKE', '%'.$value.'%')
                                ->orWhere('phone_number', 'LIKE', '%'.$value.'%')
                                ->orWhere('alternate_phone_number', 'LIKE', '%'.$value.'%');
                        });
                    }
                    elseif($condition == 'status'){
                        if($value == "Yes")
                            $collection->whereNotNull('user_id');
                        elseif($value == "No")
                            $collection->whereNull('user_id');
                    }
                    else
                        $collection->where($key,$value);
                }
            }
        }
            
        return $collection;
    }

    public function import()
    {
        return view($this->views . '.import');
    }
    
    public function import_save(Request $request)
    {	
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        try {
            \Excel::import(new LeadImport, $file);
            return redirect()->to(route('admin.leads.index'))->withSuccess('Excel successfully imported');
        }
        catch(\Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }

}