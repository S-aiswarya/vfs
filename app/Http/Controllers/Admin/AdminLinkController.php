<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use App\Models\AdminLink;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use View, Redirect, Validator, DB;

class AdminLinkController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new AdminLink;
        $this->route = 'admin.admin-links';
        $this->views = 'admin.admin_links';

        $this->permissions = ['list'=>'admin_links_listing', 'create'=>'admin_links_adding', 'edit'=>'admin_links_editing', 'delete'=>'admin_links_deleting'];

        $this->resourceConstruct();

    }

    public function index($id=null)
    {
        $links = AdminLink::where('parent_id', '=', 0)->orderBy('display_order')->get();
        $permissions = Permission::all();
        $obj = $this->model;
        if($id)
        {
            $obj = $this->model->find(decrypt($id));
            if(!$obj)
                return abort('404');
        }

        return view($this->views . '.index')->with('links', $links)->with('permissions', $permissions)->with('obj', $obj);
    }
    
    protected function getCollection() {}

    protected function setDTData($collection) {}

    protected function getSearchSettings(){}

    public function show($id)
    {
        $this->edit($id);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->model->validate();
        $this->model->fill($data);
        $this->model->save();
        return Redirect::to(route('admin.admin-links.index', array('id'=>encrypt($this->model->id))))->withSuccess('Admin link successfully saved!');
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $id = decrypt($data['id']);
        $this->model->validate($data, $id);
        if($obj = $this->model->find($id)){
            $obj->update($data);
            return Redirect::to(route('admin.admin-links.index', array('id'=>encrypt($obj->id))))->withSuccess('Admin link successfully updated!');
        } else {
            return Redirect::back()
                        ->withErrors("Ooops..Something wrong happend.Please try again.") // send back all errors to the login form
                        ->withInput(Input::all());
        }
    }

    public function order_store(Request $request)
    {
        $data = $request->all();
        $order = explode(',', $data['order']);
        foreach ($order as $key => $value) {
            DB::table('admin_links')->where('id', $value)->update(['display_order' => $key]);
        }
        $response = response()->json(['success'=>'Admin links successfully re-ordered!']);
        return $response;
    }

}