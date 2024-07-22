<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use DB;
use App\Traits\BCIE;

class DashboardController extends Controller
{
    use BCIE;

    public function index(Request $request){
        $office = $request->office;

        $week_starts_from = $request->week_starts_from;
        $week_ends_on = $request->week_ends_on;

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $assign_to_user = $request->assign_to_user_id;

        if($request->type == 'weekly_leads'){
            $weekly_leads = $this->weeklyLeads($week_starts_from, $week_ends_on, $office);
            return response()->json(['data' => $weekly_leads]);
        }
        if($request->type == 'weekly_leads_by_stage'){
            $weekly_leads_by_stage = $this->weeklyLeadsByStage($week_starts_from, $week_ends_on, $office);
            return response()->json(['data' => $weekly_leads_by_stage]);
        }
        if($request->type == 'leads_by_stage'){
            $leads_by_stage = $this->leadsByStage($date_from, $date_to, $office);
            return response()->json(['data' => $leads_by_stage]);
        }
        if($request->type == 'lead_by_source'){
            $lead_by_source = $this->leadsBySource($date_from, $date_to, $office);
            return response()->json(['data' => $lead_by_source]);
        }

        if($request->type == 'weekly_applications'){
            $weekly_applications = $this->weeklyApplications($week_starts_from, $week_ends_on);
            return response()->json(['data' => $weekly_applications]);
        }
        if($request->type == 'applications_by_stages'){
            $applications_by_stages = $this->applicationsByStage(from: $date_from, to: $date_to, assign_to_user: $assign_to_user);
            return response()->json(['data' => $applications_by_stages]);
        }

    }

    protected function checkAccess($item){
        if(auth()->user()->tokenCan('role:sales')){
            $item = $item->whereHas('lead', function($query){
                $query->where('leads.assign_to_user_id', auth()->user()->id);
            });
        }
        elseif(auth()->user()->tokenCan('role:manager')){
            $offices = $this->getAuthOffices();
            $item = $item->whereHas('lead', function($query) use($offices){
                $query->whereIn('leads.assign_to_office_id', $offices);
            });
        }

        return $item;
    }

    protected function weeklyLeads($week_starts_from, $week_ends_on, $office=null){
        $week_array = $this->processWeekArray($week_starts_from, $week_ends_on);
        $weekly_leads = [];
        foreach($week_array as $day){
            $leads = DB::table('leads');
            $leads = $this->checkAccess($leads);
            if($office)
                $leads->where('assign_to_office_id', $office);
            $leads = $leads->whereRaw("date(created_at)='".$day."'")->count();
            $weekly_leads[] = ['day'=>$day, 'count'=>$leads];
            
        }
        return $weekly_leads;
    }

    protected function processWeekArray($week_starts_from, $week_ends_on){
        $week_array = [];
        $process_week = true;
        $i = 0;
        while($process_week){
            if($i == 0)
                $week_day = $week_starts_from;
            else
                $week_day = date('Y-m-d', strtotime($week_starts_from . ' +'.$i.' day'));
            $week_array[] = $week_day;
            $i++;
            if($week_day == $week_ends_on)
                $process_week = false;
        }
        return $week_array;
    }

    protected function weeklyLeadsByStage($week_starts_from, $week_ends_on, $office=null){
        $weekly_leads_by_stage = [];

        $stages = DB::table('stages')->select('id', 'name', 'colour')->whereIn('id', [1, 3, 6, 7])->get();
        foreach($stages as $stage){
            $lead_count = DB::table('leads')
                                ->where('stage_id', $stage->id)
                                ->whereRaw($this->leadDateQuery($week_starts_from, $week_ends_on));
            if($office)
                $lead_count->where('assign_to_office_id', $office);
            $lead_count = $this->checkAccess($lead_count);
            $lead_count = $lead_count->count();

            $data = [
                'id' => $stage->id,
                'name' => $stage->name,
                'colour' => $stage->colour,
                'lead_count' => $lead_count,
            ];
            $weekly_leads_by_stage[] = $data;
        }

        return $weekly_leads_by_stage;
    }

    protected function leadsByStage($from, $to, $office=null){
        $leads_by_stage = [];

        $stages = DB::table('stages')->select('id', 'name', 'colour')->where('status', 1)->where('type', 'Lead')->orderBy('processing_order')->get();

        foreach($stages as $stage){

            $lead_count = DB::table('leads')
                                ->where('stage_id', $stage->id)
                                ->whereRaw($this->leadDateQuery($from, $to));
            if($office)
                $lead_count->where('assign_to_office_id', $office);
            $lead_count = $this->checkAccess($lead_count);
            $lead_count = $lead_count->count();

            $data = [
                'id' => $stage->id,
                'name' => $stage->name,
                'colour' => $stage->colour,
                'lead_count' => $lead_count,
            ];
            $leads_by_stage[] = $data;
        }

        return $leads_by_stage;
    }

    protected function leadsBySource($from, $to, $office=null){
        $lead_sources = DB::table('lead_sources')->select('id', 'name')->where('status', 1)->get();
        $lead_source_array = [];
        $total_leads = 0;
        foreach($lead_sources as $lead_source){

            $lead_count = DB::table('leads')
                                ->where('source_id', $lead_source->id)
                                ->whereRaw($this->leadDateQuery($from, $to));
            if($office)
                $lead_count->where('assign_to_office_id', $office);

            $lead_count = $this->checkAccess($lead_count);
            $lead_count = $lead_count->count();

            $data = [
                'id' => $lead_source->id,
                'name' => $lead_source->name,
                'lead_count' => $lead_count,
            ];
            $total_leads = $total_leads+$data['lead_count'];
            $lead_source_array[] = $data;
        }

        $lead_by_source = [];
        $others_count = 0;
        foreach($lead_source_array as $lead_source){
            if($lead_source['id']<=5){
                $lead_by_source[] = ['source'=>$lead_source['name'], 'value'=>($total_leads)?($lead_source['lead_count']/$total_leads)*100:0];
            }
            else
                $others_count = $others_count+$lead_source['lead_count'];
        }
        $lead_by_source[] = ['source'=>'Others', 'value'=>($total_leads)?($others_count/$total_leads)*100:0];

        return $lead_by_source;
    }

    protected function weeklyApplications($week_starts_from, $week_ends_on){
        $week_array = $this->processWeekArray($week_starts_from, $week_ends_on);
        $weekly_applications = [];
        foreach($week_array as $day){
            $applications = DB::table('applications')->join('leads', 'applications.lead_id', '=', 'leads.id');

            $applications = $this->checkAccess($applications);

            $applications = $applications->whereRaw("date(applications.created_at)='".$day."'")
                                            ->count();
            
            $weekly_applications[] = ['day'=>$day, 'count'=>$applications];
        }
        return $weekly_applications;
    }

    protected function applicationsByStage($from, $to, $assign_to_user=null){
        $applications_by_stage = [];

        $stages = DB::table('stages')->select('id', 'name', 'colour')->where('status', 1)->where('type', 'Visa')->orderBy('processing_order')->get();

        foreach($stages as $stage){
            $application_count = DB::table('applications')->join('leads', 'applications.lead_id', '=', 'leads.id')->where('applications.stage_id', $stage->id);
            if($assign_to_user)
                $application_count->where('leads.assign_to_user_id', $assign_to_user);
            $application_count = $this->checkAccess($application_count);

            $application_count = $application_count->whereRaw($this->applicationDateQuery($from, $to))->count();

            $data = [
                'id' => $stage->id,
                'name' => $stage->name,
                'colour' => $stage->colour,
                'application_count' => $application_count,
            ];

            $applications_by_stage[] = $data;
        }

        return $applications_by_stage;
    }

    protected function leadDateQuery($from, $to){
        return 'date(leads.created_at) >= "'.$from.'" AND date(leads.created_at) <= "'.$to.'"';
    }

    protected function applicationDateQuery($from, $to){
        return 'date(applications.created_at) >= "'.$from.'" AND date(applications.created_at) <= "'.$to.'"';
    }

    public function documentStatus(){
        $lead = auth()->user()->lead;

        $document_status = [];
        $document_status[0]['name'] = 'Requested';
        $document_status[0]['count'] = Document::where('lead_id', $lead->id)->where('status', 'Requested')->count();

        $document_status[1]['name'] = 'Rejected';
        $document_status[1]['count'] = Document::where('lead_id', $lead->id)->where('status', 'Rejected')->count();

        $document_status[2]['name'] = 'Accepted';
        $document_status[2]['count'] = Document::where('lead_id', $lead->id)->where('status', 'Accepted')->count();

        return response()->json(['data' => $document_status]);
    }
}
