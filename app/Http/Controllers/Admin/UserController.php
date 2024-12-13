<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Application;
use App\Models\Lead;
use App\Models\Office;
use App\Traits\ResourceTrait;
use App\Models\User;
use App\Models\Role;
use App\Models\Center;
use App\Models\UserTarget;
use App\Services\ApplicationService;
use App\Services\LeadService;
use Illuminate\Support\Facades\Hash;
use DB, View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResourceTrait;

    public function __construct()
    {
        parent::__construct();

        $this->model = new User;
        $this->route = 'admin.users';
        $this->views = 'admin.users';

        $this->permissions = ['list'=>'user_listing', 'create'=>'user_adding', 'edit'=>'user_editing', 'delete'=>'user_deleting'];

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
        return $this->model->select('users.*','roles.name as role_name', 'office_countries.name as office_country')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->leftJoin('office_countries', 'users.office_country_id', '=', 'office_countries.id')
                    ->where('users.user_type','user');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->rawColumns(['action_ajax_edit', 'action_delete', 'status']);
    }

    protected function getSearchSettings(){}

    public function create()
    {
        $roles = Role::where('guard_name', 'user')->get();
        $targets = [];
        return view($this->views . '.form')->with('obj', $this->model)->with('roles', $roles)->with('targets', $targets);
    }

    public function edit($id) {
        $id =  decrypt($id);
        if($obj = $this->model->find($id)){
            $roles = Role::where('guard_name', 'user')->get();
            $targets = DB::table('targets')->get();
            return view($this->views . '.form')->with('obj', $obj)->with('roles', $roles)->with('targets', $targets);
        } else {
            return $this->redirect('notfound');
        }
    }

    public function modifyStatus($id) {
        $id =  decrypt($id);
        if($obj = $this->model->find($id)){
            $users = User::where('role_id', $obj->role_id)->where('id', '!=', $obj->id)->get();
            return view($this->views . '.change_status')->with('obj', $obj)->with('users', $users);
        } else {
            return $this->redirect('notfound');
        }
    }

    public function updateModifiedStatus(Request $request){
        $from = User::find($request->id);
        $to = User::find($request->user_id);
        if($from && $to){
            if($from->role_id == 5){
                if($this->changeCounselor($from, $to)){
                    $from->status = 0;
                    if($from->save())
                        return response()->json(['success'=>true, 'message' => 'User successfully disabled']);
                }
            }
            if($from->role_id == 6){
                if($this->changeAppCoordinator($from, $to)){
                    $from->status = 0;
                    if($from->save())
                        return response()->json(['success'=>true, 'message' => 'User successfully disabled']);
                }
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    protected function changeAppCoordinator($from, $to){
        $applications = Application::where('app_coordinator_id', $from->id)->get();
        foreach($applications as $application){
            $application_service = new ApplicationService();
            $application_service->changeAppCoordinator($application, $to);
        }

        $remaining_applications = Application::where('app_coordinator_id', $from->id)->count();

        if(!$remaining_applications)
            return true;
        else
            return false;
    }

    protected function changeCounselor($from, $to){
        $leads = Lead::where('assign_to_user_id', $from->id)->get();
        foreach($leads as $lead){
            $lead_service = new LeadService();
            $lead_service->changeCounselor($lead, $to);
        }

        $remaining_leads = Lead::where('assign_to_user_id', $from->id)->count();

        if(!$remaining_leads)
            return true;
        else
            return false;
    }

    protected function changeManager($from, $to){
        $offices = $from->offices->pluck('id')->toArray();
        if(!empty($offices)){
            DB::table('office_user')->whereIn('office_id', $offices)->delete();
            $to->offices()->attach($offices);
        }
        $remaining_offices = DB::table('office_user')->where('user_id', $from->id)->count();
        if(!$remaining_offices)
            return true;
        else
            return false;
    }

    public function store(UserRequest $request)
    {
        $request->validated();
        $data = $request->all();

        $data['password'] = Hash::make($data['password']);
        $data['user_type'] = 'user';
        $this->model->fill($data);
        if($this->model->save())
        {
            if(!empty($data['offices']))
            {
                if($data['role_id'] == 6){
                    foreach($data['offices'] as $office){
                        $this->saveOfficeAppCoordinator($office, $this->model->id);
                    }
                }
                else
                    $this->model->offices()->attach($data['offices']);
            }
                

            if(!empty($data['counsellors'])){
                $this->saveCounsellors($data['counsellors'], $this->model->id);
            }
            return response()->json($this->_renderEdit($this->model, 'User successfully saved.'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(UserRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $id = decrypt($data['id']);

        if($obj = $this->model->find($id)){
            
            if($data['password'] != '')
                $data['password'] = Hash::make($data['password']);
            else
                unset($data['password']);

            if($obj->update($data))
            {
                $offices = !empty($data['offices'])?$data['offices']:[];
                if($data['role_id'] == 6){
                    $app_coordinator_offices = $obj->applicationCoordinatorOffices->pluck('id')->toArray();

                    $offices_to_remove = array_diff($app_coordinator_offices, $offices);
                    if($offices_to_remove)
                        DB::table('offices')->whereIn('id', $offices_to_remove)->update(['application_coordinator_id'=>null]);
                    
                    if($offices)
                        foreach($offices as $office){
                            $this->saveOfficeAppCoordinator($office, $obj->id);
                        }
                }
                else
                    $obj->offices()->sync($offices);

                if(!empty($data['counsellors'])){
                    $this->saveCounsellors($data['counsellors'], $obj->id);
                }
                if($obj->role_id == 5){
                    $this->saveTargets($data, $obj->id);
                }
                return response()->json($this->_renderEdit($obj, 'User successfully updated.'));
            }
            return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
        } else {
            return $this->redirect('notfound');
        }
    }

    protected function saveOfficeAppCoordinator($office_id, $user_id){
        $office = Office::find($office_id);
        $office->application_coordinator_id = $user_id;
        $office->save();
    }

    protected function saveCounsellors($counsellors, $manager){
        if(count($counsellors)){
            foreach($counsellors as $counsellor){
                $user = User::find($counsellor);
                $user->manager_id = $manager;
                $user->save();
            }
        }
    }

    protected function saveTargets($data, $counsellor){
        $targets = DB::table('targets')->get();
        foreach ($targets as $key => $target) {
            if(!empty($data['target_'.$target->id])){
                $user_target = new UserTarget;
                $user_target->target_id = $target->id;
                $user_target->user_id = $counsellor;
                $user_target->target_count = $data['target_'.$target->id];
                $user_target->save();
            }
        }
    }

    protected function assignRole($role, $obj){
        $data = ['role_id' => $role, 'model_type' => User::class, 'model_id' => $obj->id];
        DB::table('model_has_roles')->insert($data);
    }

    public function targets($id, $intake=null){
        $user_targets = null;
        if($intake)
            $user_targets = UserTarget::where('user_id', $id)->where('intake_id', $intake)->first();

        $intakes = DB::table('intakes')->get();
        $user = User::find($id);
        return view($this->views . '.targets')->with('user_id', $id)->with('user', $user)->with('user_targets', $user_targets)->with('selected_intake', $intake)->with('intakes', $intakes);
    }

    public function targetStore(Request $request){
        $user_target = UserTarget::where('user_id', $request->id)->where('intake_id', $request->intake_id)->first();
        if(!$user_target){
            $user_target = new UserTarget();
            $user_target->user_id = $request->id;
            $user_target->intake_id = $request->intake_id;
        }

        $user_target->application_submitted = $request->application_submitted;
        $user_target->unconditional_offers = $request->unconditional_offers;
        $user_target->deposit_paid = $request->deposit_paid;
        $user_target->visa_obtained = $request->visa_obtained;

        if($user_target->save()){
            $user_manager = $user_target->user?->manager;
            if($user_manager)
                $this->updateManagerTarget($user_manager->id, $user_target->intake_id);

            $html = $this->targets($user_target->user_id, $user_target->intake_id)->render();
            return response()->json(['title'=>'Targets of '.$user_target->user->name, 'html' => $html, 'message' => 'Targets successfully configured!']);
        }

        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    protected function updateManagerTarget($manager_id, $intake){
        $application_submitted = $unconditional_offers = $deposit_paid = $visa_obtained = 0;
        $user_targets = DB::table('user_targets')
                            ->select('user_targets.*')
                            ->join('users', 'users.id', '=', 'user_targets.user_id')
                            ->where('users.manager_id', $manager_id)
                            ->where('user_targets.intake_id', $intake)
                            ->get();

        foreach($user_targets as $target){
            $application_submitted = $application_submitted+$target->application_submitted;
            $unconditional_offers = $unconditional_offers+$target->unconditional_offers;
            $deposit_paid = $deposit_paid+$target->deposit_paid;
            $visa_obtained = $visa_obtained+$target->visa_obtained;
        }

        $manager_target = UserTarget::where('user_id', $manager_id)->where('intake_id', $intake)->first();
        if(!$manager_target){
            $manager_target = new UserTarget();
            $manager_target->user_id = $manager_id;
            $manager_target->intake_id = $intake;
        }

        $manager_target->application_submitted = $application_submitted;
        $manager_target->unconditional_offers = $unconditional_offers;
        $manager_target->deposit_paid = $deposit_paid;
        $manager_target->visa_obtained = $visa_obtained;
        $manager_target->save();
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
                    if($condition == 'user_office')
                    {
                        $collection->whereRaw('CASE WHEN users.role_id = 6 THEN EXISTS(SELECT offices.id FROM offices WHERE offices.application_coordinator_id=users.id AND offices.id='.$value.') ELSE EXISTS(SELECT office_user.office_id FROM office_user WHERE office_user.user_id=users.id AND office_user.office_id='.$value.') END');
                    }
                    else
                        $collection->where($key,$value);
                }
            }
        }
            
        return $collection;
    }

    public function accessUnallocatedLeads($id)
    {
        $id = decrypt($id);
        $obj = $this->model->find($id);
        if ($obj) {
            $status = $obj->has_permission_to_access_unallocated_leads;
            $set_status = ($status == 1)?0:1;
            $obj->has_permission_to_access_unallocated_leads = $set_status;
            $obj->save();
            $message = ($status == 1)?"removed permission to access unassigned leads":"got permission to access unassigned leads";
            return $this->redirect($message,'success', 'index');
        }
        return $this->redirect('notfound');
    }

}
