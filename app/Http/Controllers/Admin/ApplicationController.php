<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use App\Models\Application;
use App\Models\Stage;
use DB, View;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new Application();
        $this->route = 'admin.applications';
        $this->views = 'admin.applications';
        $this->permissions = ['list'=>'traveler_listing', 'create'=>'', 'edit'=>'', 'delete'=>''];
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
            return View::make($this->views . '.index', array('search_settings'=>$search_settings));
        }
    }
    
    protected function getCollection() {
        return $this->model->select('applications.*', 'stages.name as stage', 'leads.id as lead_id')
                            ->join('leads', 'leads.id', '=', 'applications.lead_id')
                            ->leftJoin('stages', 'stages.id', '=', 'applications.stage_id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
             ->rawColumns(['action_ajax_edit', 'action_delete']);
    }

    protected function getSearchSettings(){
        $settings = [];
        $settings['stages'] = Stage::where('parent_id', 0)->where('type', 'Visa')->get();
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
                            $query->where('applications.name', 'LIKE', '%'.$value.'%')
                                ->orWhere('applications.email', 'LIKE', '%'.$value.'%')
                                ->orWhere('applications.phone_number', 'LIKE', '%'.$value.'%')
                                ->orWhere('applications.passport_number', 'LIKE', '%'.$value.'%');
                        });
                    }
                    else
                        $collection->where($key,$value);
                }
            }
        }
            
        return $collection;
    }

}
