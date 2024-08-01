<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\Apis\AgencyResourceCollection;
use App\Http\Resources\Apis\CountryResourceCollection;
use App\Http\Resources\Apis\CourseResourceCollection;
use App\Http\Resources\Apis\StageResourceCollection;
use App\Http\Resources\Apis\SubjectAreaResource;
use App\Http\Resources\Apis\SubjectAreaResourceCollection;
use App\Http\Resources\Apis\SubstageResourceCollection;
use App\Http\Resources\Apis\UniversityResourceCollection;
use App\Http\Resources\Apis\UserResourceCollection;
use App\Http\Resources\ApplicationBaseResourceCollection;
use App\Http\Resources\CourseLevelResourceCollection;
use App\Http\Resources\DocumentTemplateResourceCollection;
use App\Http\Resources\IntakeResourceCollection;
use App\Http\Resources\RegisterTypeGroupResourceCollection;
use App\Http\Resources\CityResourceCollection;
use App\Http\Resources\LocationResourceCollection;
use App\Http\Resources\CenterResourceCollection;
use App\Http\Resources\GateResourceCollection;
use App\Models\Agency;
use App\Models\Application;
use App\Models\Gate;
use App\Models\Center;
use App\Models\Location;
use App\Models\City;
use App\Models\Country;
use App\Models\Course;
use App\Models\DocumentTemplate;
use App\Models\Stage;
use App\Models\SubStage;
use App\Models\University;
use App\Models\User;
use App\Models\CountryList;
use App\Models\ReferralList;
use App\Models\CourseLevel;
use App\Models\Intake;
use App\Models\LeadSource;
use App\Models\Register_type;
use App\Models\Role;
use App\Models\SubjectArea;
use App\Models\TitleList;
use Illuminate\Http\Request;
use DB;

class ListController extends Controller
{
 
    public function register_types(Request $request){
        $register_types = Register_type::select('id','key_id','register_name','group_name','check_out','sort_order');
        if($request->keyword)
           $register_types->where('register_name', 'LIKE', '%'.$request->keyword.'%');
        $register_types = $register_types->get();
        return response()->json(['data' => $register_types]);
    }

    public function register_type_groups(Request $request){
        $groups = Register_type::select('group_name')->groupBy('group_name')->get();
        foreach($groups as $group){
            $group->register_types = Register_type::select('id','key_id','register_name','check_out')
                                ->where('group_name', $group->group_name)->orderBy('sort_order')->get();
        }
        return new RegisterTypeGroupResourceCollection($groups);
    }

    public function countries(Request $request){
        $countries = new Country;
        if($request->keyword)
            $countries = $countries->where('name', 'LIKE', '%'.$request->keyword.'%');
        $countries = $countries->get();
        return new CountryResourceCollection($countries);
    }

    public function cities(Request $request){
        $cities = new City;
        if($request->country_id)
            $cities = $cities->where('country_id', $request->country_id);
        if($request->keyword)
            $cities = $cities->where('name', 'LIKE', '%'.$request->keyword.'%');
        $cities = $cities->get();
        return new CityResourceCollection($cities);
    }

    public function locations(Request $request){
        $locations = new Location;
        if($request->city_id)
            $locations = $locations->where('city_id', $request->city_id);
        if($request->keyword)
            $locations = $locations->where('name', 'LIKE', '%'.$request->keyword.'%');
        $locations= $locations->get();
        return new LocationResourceCollection($locations);
    }
   
    public function centers(Request $request){
        $centers = new Center;
        if($request->location_id)
        $centers = $centers->where('location_id', $request->location_id);
        if($request->keyword)
        $centers = $centers->where('name', 'LIKE', '%'.$request->keyword.'%');
        $centers= $centers->get();
        return new CenterResourceCollection($centers);
    }

    public function gates(Request $request){
        $gates = new Gate;
        if($request->center_id)
        $gates = $gates->where('center_id', $request->center_id);
        if($request->keyword)
        $gates = $gates->where('name', 'LIKE', '%'.$request->keyword.'%');
        $gates= $gates->get();
        return new GateResourceCollection($gates);
    }
    

    public function applications(Request $request){
        $applications = new Application;
        if($request->lead_id)
            $applications = $applications->where('lead_id', $request->lead_id);
        if($request->keyword)
            $applications = $applications->where('name', 'LIKE', '%'.$request->keyword.'%');
        $applications = $applications->get();
        return new ApplicationBaseResourceCollection($applications);
    }

    public function subject_areas(Request $request){
        $courses = SubjectArea::where('status', 1);
        if($request->keyword)
            $courses->where('name', 'LIKE', '%'.$request->keyword.'%');

        if($request->course_level_id)
            $courses->where('course_level_id', $request->course_level_id);

        $courses = $courses->get();
        return new SubjectAreaResourceCollection($courses);
    }

    public function stages(Request $request){
        $stages = Stage::where('status', 1)->where('parent_id', 0);
        if($request->changable)
            $stages->where('changable', 1);
        if($request->type)
            $stages->where('type', $request->type);
        if($request->keyword)
            $stages->where('name', 'LIKE', '%'.$request->keyword.'%');
        $stages = $stages->orderBy('processing_order')->get();
        return new StageResourceCollection($stages);
    }

    public function nextStages(Request $request, $current_stage){
        $stage = Stage::where('status', 1)->where('id', $current_stage);
        if($request->keyword){
            $keyword = $request->keyword;
            $stage->with(["nextPossibleStages" => function($query) use($keyword){
                $query->where('name', 'LIKE', '%'.$keyword.'%')->where('changable', 1);
            }]);
        }
        else{
            $stage->with(["nextPossibleStages" => function($query){
                $query->where('changable', 1);
            }]);
        }
        $stage = $stage->first();
        return new StageResourceCollection($stage?->nextPossibleStages);
    }

    public function substages(Request $request){
        $substages = SubStage::where('status', 1);
        if($request->keyword)
            $substages->where('name', 'LIKE', '%'.$request->keyword.'%');
        if($request->stage)
            $substages->where('parent_id', $request->stage);
        $substages = $substages->get();
        return new SubstageResourceCollection($substages);
    }

    public function agencies(Request $request){
        $agencies = Agency::where('status', 1);
        if($request->keyword)
            $agencies->where('name', 'LIKE', '%'.$request->keyword.'%');
        $agencies = $agencies->get();
        return new AgencyResourceCollection($agencies);
    }

    public function users(Request $request){
        $users = User::where('status', 1)->where('user_type', 'user');
        if($request->keyword)
            $users->where('name', 'LIKE', '%'.$request->keyword.'%');
        if($request->role_id)
            $users->where('role_id', $request->role_id);
        if($request->manager_id)
            $users->where('manager_id', $request->manager_id);

        if($request->office_id){
            $office_id = $request->office_id;
            if($request->role_id && $request->role_id == 6){
                $users->whereHas('applicationCoordinatorOffices', function($query) use($office_id){
                    $query->where('offices.id', $office_id);
                });
            }
            else{
                $users->whereHas('offices', function($query) use($office_id){
                    $query->where('office_user.office_id', $office_id);
                });
            }
        }
        $users = $users->get();
        return new UserResourceCollection($users);
    }

    public function documentTemplates(Request $request){
        $templates = DocumentTemplate::where('status', 1);
        if($request->type)
            $templates->where('type', $request->type);
        if($request->keyword)
            $templates->where('name', 'LIKE', '%'.$request->keyword.'%');
        $templates = $templates->get();
        return new DocumentTemplateResourceCollection($templates);
    }

    public function globalCountries(Request $request){
        $countries = CountryList::select('id', 'name', 'phonecode');
        if($request->keyword)
            $countries->where('name', 'LIKE', '%'.$request->keyword.'%');
        $countries = $countries->get();
        return response()->json(['data' => $countries]);
    }

    public function referrals(Request $request){
        $referrals = ReferralList::select('id', 'name');
        if($request->keyword)
            $referrals->where('name', 'LIKE', '%'.$request->keyword.'%');
        $referrals = $referrals->get();
        return response()->json(['data' => $referrals]);
    }

    public function courseLevels(Request $request){
        $items = CourseLevel::where('status', 1);
        if($request->keyword)
            $items->where('name', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return new CourseLevelResourceCollection($items);
    }

    public function intakes(Request $request){
        $intakes = Intake::select('id', 'month', 'year', 'is_default');
        $intakes = $intakes->where('status', 1)->get();
        return new IntakeResourceCollection($intakes);
    }

    public function titles(Request $request){
        $titles = TitleList::select('id', 'name');
        if($request->keyword)
            $titles->where('name', 'LIKE', '%'.$request->keyword.'%');
        $titles = $titles->get();
        return response()->json(['data' => $titles]);
    }

    public function leadSources(Request $request){
        $items = LeadSource::select('id', 'name')->where('status', 1);
        if($request->keyword)
            $items->where('name', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function roles(Request $request){
        $items = Role::select('id', 'name')->where('guard_name', 'user');
        if($request->keyword)
            $items->where('name', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function permissions(Request $request){
        $items = DB::table('app_permissions')->select('id', 'title');
        if($request->keyword)
            $items->where('title', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function emailTemplates(Request $request){
        $items = DB::table('email_templates')->select('id', 'name');
        if($request->keyword)
            $items->where('name', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function whatsappTemplates(Request $request){
        $items = DB::table('whatsapp_templates')->select('id', 'title')->where('approved', 1);
        if($request->keyword)
            $items->where('title', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function offices(Request $request){
        $items = DB::table('offices')->select('id', 'name');
        if($request->keyword)
            $items->where('name', 'LIKE', '%'.$request->keyword.'%');
        if(auth()->user()->tokenCan('role:manager') || auth()->user()->tokenCan('role:consellor')){
            $items->whereIn('id', $this->getAuthOffices());
        }
        if(auth()->user()->tokenCan('role:app-coordinator'))
            $items->where('application_coordinator_id', auth()->user()->id);
        
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function events(Request $request){
        $items = DB::table('events')->select('id', 'name')->where('status', 1);
        if($request->keyword)
            $items->where('name', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function lead_archive_reasons(Request $request){
        $items = DB::table('lead_archive_reasons')->select('id', 'reason');
        if($request->keyword)
            $items->where('reason', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function phone_call_statuses(Request $request){
        $items = DB::table('phone_call_statuses')->select('id', 'status');
        if($request->keyword)
            $items->where('status', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->get();
        return response()->json(['data' => $items]);
    }

    public function universityCountries(Request $request){
        $items = DB::table('universities')->select('countries.id', 'countries.name')->join('countries', 'countries.id', '=', 'universities.country_id');
        if($request->keyword)
            $items->where('countries.name', 'LIKE', '%'.$request->keyword.'%');
        $items = $items->where('status', 1)->groupBy('countries.id')->get();
        return response()->json(['data' => $items]);
    }

    public function students(Request $request){
        $items = DB::table('leads')->select('id', 'name', 'student_code')->whereNotNull('user_id');
        if($request->keyword){
            $keyword = $request->keyword;
            $items->where(function($query) use($keyword){
                $query->where('name', 'LIKE', '%'.$keyword.'%')->orWhere('student_code', 'LIKE', '%'.$keyword.'%');
            });
        }
        $items = $items->get();
        return response()->json(['data' => $items]);
    }
}
