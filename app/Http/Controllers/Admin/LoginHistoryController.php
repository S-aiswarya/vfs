<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginHistory;
use View,Redirect, DB;

class LoginHistoryController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new LoginHistory;
        $this->route .= '.login-history';
        $this->views .= '.login_history';

        $this->permissions = ['list'=>'login_history_listing', 'create'=>'login_history_adding','edit'=>'login_history_editing', 'delete'=>'login_history_deleting'];
        $this->resourceConstruct();

    }

    protected function getCollection() {
        return $this->model->select('login_history.*','users.name as user_name')->join('users','users.id','=', 'login_history.user_id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->addColumn('date', function($obj){
                if($obj->created_at)
                    return date('d M, Y h:i A', strtotime($obj->created_at));
                else
                    return '';
            })
            ->orderColumn('date', function ($query, $order) {
                     $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action_delete']);
    }

    protected function getSearchSettings(){
    }

}
