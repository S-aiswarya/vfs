<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Input, View, Validator, Redirect, Auth, DB, Session, Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Widget;
use App\Models\User;
use App\Events\LoginHistory;
use App\Models\Setting;

class WebadminController extends Controller {

    public function __construct(){
        $this->middleware('permission:widgets', ['only' => ['widgets','save_widget']]);
    }
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $lead_count = DB::table('leads')->count();
        $application_count = DB::table('applications')->count();
        $weekly_leads = DB::table('leads')->select(DB::raw('COUNT(`id`) AS lead_count'), DB::raw('DATE(`created_at`) AS created_date'))
                            ->where('created_at', '>=', now()->subDays(7)->setTime(0, 0, 0)->toDateTimeString())
                            ->groupBy('created_date')->get();

        $last_7_days = $this->get7DaysDates(7);
        $leads = [];
        foreach($last_7_days as $key=> $day)
        {
            $leads[$key] = 0;
            foreach ($weekly_leads as $lead) {
                if($day == $lead->created_date)
                    $leads[$key] = floatval($lead->lead_count);
            }
        }
        $leads = json_encode($leads);

        $weekly_applications = DB::table('applications')->select(DB::raw('COUNT(`id`) AS application_count'), DB::raw('DATE(`created_at`) AS created_date'))
                            ->where('created_at', '>=', now()->subDays(7)->setTime(0, 0, 0)->toDateTimeString())
                            ->groupBy('created_date')->get();

        $application_array = [];
        foreach($last_7_days as $key=> $day)
        {
            $application_array[$key] = 0;
            foreach ($weekly_applications as $application) {
                if($day == $application->created_date)
                    $application_array[$key] = floatval($application->application_count);
            }
        }
        $applications = json_encode($application_array);

		return view('admin.index', compact('lead_count', 'application_count', 'leads', 'applications'));
	}

    protected function get7DaysDates($days, $format = 'Y-m-d'){
	    $m = date("m"); 
	    $de= date("d"); 
	    $y= date("Y");
	    $dateArray = array();
	    for($i=0; $i<=$days-1; $i++){
	        $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)); 
	    }
	    return array_reverse($dateArray);
	}

	public function login()
	{
		if(Auth::guard('admin')->user())
		{
            $admin_url = Config::get('admin.url_prefix').'/dashboard';
			return Redirect::to($admin_url);
		}
		else{
			return view('admin.login');
		}
	}

    public function google_login(Request $request){
        $id_token = $request->credential;
        $google_client_id = Setting::where('code', 'google_auth_client_id')->value('value_text');
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client = new \Google\Client();
        $client->setClientId($google_client_id);
        $client->setHttpClient($guzzleClient);
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            if(empty($payload['email']))
                return Redirect::back()->withErrors('Invalid Login');
            $email = $payload['email'];
            $user = User::where('email', $email)->where('status', 1)->first();
            if(!$user)
                return Redirect::back()->withErrors('Invalid Login');
            Auth::guard('admin')->login($user);
            event(new LoginHistory(['email'=>$email], 'admin'));
            $request->session()->regenerate();
            $admin_url = Config::get('admin.url_prefix').'/dashboard';
            return redirect()->intended($admin_url);

        } else {
            return Redirect::back()->withErrors('Invalid Login');
        }
    }

    public function select2_countries(Request $request){
        $items = DB::table('office_countries')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }
    
    public function select2_cities(Request $request){

        $items = DB::table('cities')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at');
      
          if(!empty($request->office_country_id))
            $items->where('country_id',$request->office_country_id);
       $items->get();

        return json_encode($items);
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }




    public function select2_locations(Request $request){
        $items = DB::table('locations')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_key_types(Request $request){
        $items = DB::table('key_types')->where('key_name', 'like', $request->q.'%')->orderBy('key_name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->key_name];
        }
        return \Response::json($json);
    }
    public function select2_register_types(Request $request){
        $items = DB::table('register_types')->where('register_name', 'like', $request->q.'%')->orderBy('register_name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->register_name];
        }
        return \Response::json($json);
    }

    public function select2_employees(Request $request){
        $items = DB::table('employees')->where('employ_name', 'like', $request->q.'%')->orderBy('employ_name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->employ_name];
        }
        return \Response::json($json);
    }

    public function select2_check_in_type(Request $request){
        $items = DB::table('check_in_type')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_centers(Request $request){
        $items = DB::table('centers')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_gates(Request $request){
        $items = DB::table('gates')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }
    


    public function select2_global_countries(Request $request){
        $items = DB::table('countries')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_courses(Request $request){
        $items = DB::table('courses')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_universities(Request $request){
        $items = DB::table('universities')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_email_templates(Request $request){
        $items = DB::table('email_templates')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_user_roles(Request $request){
        $items = DB::table('roles')->where('guard_name', 'user')->where('name', 'like', $request->q.'%')->orderBy('name')->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_stages(Request $request, $type=null){
        $items = DB::table('stages')->where('parent_id', 0)->where('name', 'like', $request->q.'%');
        if($type)
            $items->where('type', $type);
        $items = $items->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_users(Request $request, $role=null){
        $items = DB::table('users')->where('name', 'like', $request->q.'%')->where('user_type', 'user');
        if($role)
            $items->where('role_id', $role);
        $items = $items->orderBy('name')->whereNull('deleted_at')->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    
    public function select2_course_levels(Request $request){
        $items = DB::table('course_levels')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_branches(Request $request){
        $items = DB::table('offices')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_agencies(Request $request){
        $items = DB::table('agencies')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function select2_intakes(Request $request){
        $items = DB::table('intakes')->where('month', 'like', $request->q.'%')->orderBy('Year', 'DESC')->orderBy('month')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->month.' '.$c->year];
        }
        return \Response::json($json);
    }

    public function select2_subject_areas(Request $request){
        $items = DB::table('subject_areas')->where('name', 'like', $request->q.'%')->orderBy('name')->whereNull('deleted_at')
            ->get();
        $json = [];
        foreach($items as $c){
            $json[] = ['id'=>$c->id, 'text'=>$c->name];
        }
        return \Response::json($json);
    }

    public function unique_roles(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
         
        $where = "name='".$name."'";
        if($id)
            $where .= " AND id != ".decrypt($id);
        $obj = DB::table('roles')
                    ->whereRaw($where)
                    ->get();
         
        if (count($obj)>0) {  
             echo "false";
        } else {  
             echo "true";
        }
    }

    public function unique_users(Request $request)
    {
        $id = $request->id;
        $email = $request->email;
         
        $where = "email='".$email."'";
        if($id)
            $where .= " AND id != ".decrypt($id);
        $obj = DB::table('admins')
                    ->whereRaw($where)
                    ->whereNull('deleted_at')
                    ->get();
         
        if (count($obj)>0) {  
             echo "false";
        } else {  
             echo "true";
        }
    }

	public function unique_slug(Request $request)
    {
         $id = $request->id;
         $slug = $request->slug;
         $table = $request->table;
         
         $where = "slug='".$slug."'";
         if($id)
            $where .= " AND id != ".decrypt($id);
         $result = DB::table($table)
                    ->whereRaw($where)
                    ->whereNull('deleted_at')
                    ->get();
         
         if (count($result)>0) {  
             echo "false";
         } else {  
             echo "true";
         }
    }
	
	public function changePassword(Request $request){
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('current_password'), $request->get('new_pwd')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6',
            'new_confirm_password' => ['same:new_password'],
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        return redirect()->back()->with("success","Password changed successfully !");
    }

    public function widgets()
    {
        $widgets = Widget::all();
        $data = [];
        foreach ($widgets as $key => $value) {
            $data[$value->code] = (array) json_decode($value->content);
        }
        return view('admin.widgets', ['data'=>$data]);
    }

    public function save_widget(Request $request)
    {
        $data = $request->all();
        if($obj = Widget::find($data['id']))
        {
            $obj->content = json_encode($data['section']);
            $obj->save();
            return Redirect::to(url('admin/widgets'))->withSuccess('Widget successfully updated!');
        }
        return Redirect::back()
                        ->withErrors("Ooops..Something wrong happend.Please try again.") // send back all errors to the login form
                        ->withInput($data);
    }

}
