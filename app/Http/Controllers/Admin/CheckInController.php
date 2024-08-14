<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CheckinExport;
use DB;
use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Requests\Admin\CheckInRequest;
use App\Models\Check_In_Type;
use App\Traits\ResourceTrait;
use App\Models\CheckIn;
use App\Models\Country;
use App\Models\City;
use App\Models\Location;
use App\Models\Center;
use App\Models\Gate;
use App\Helpers\BladeHelper;
use App\Models\Register_type;
use Illuminate\Http\Request;
use View;

class CheckInController extends Controller

{

     use ResourceTrait;
    public function __construct()
    {
        parent::__construct();

        $this->model = new CheckIn;
        $this->route = 'admin.checkins';
        $this->views = 'admin.check_ins';

        $this->permissions = ['list'=>'checkin_listing', 'create'=>'checkin_adding', 'edit'=>'checkin_editing', 'delete'=>'checkin_deleting'];

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
        return $this->model->select('check_ins.*','register_types.register_name as checkin_type')->join('register_types','register_types.id','=','check_ins.check_in_type_id');
    }

    protected function setDTData($collection) {
        $route = $this->route;
        return $this->initDTData($collection)
            ->editColumn('flag', function($obj) use ($route) { 
                if($obj->flag)
                    return '<a href="'.BladeHelper::asset($obj->flag).'" target="_blank"><img src="'.BladeHelper::asset($obj->flag).'" width="30px;"/></a>';
            })
            ->rawColumns(['action_ajax_edit', 'action_delete', 'flag']);
    }

    protected function getSearchSettings(){}

    public function store(CheckInRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $data['flag'] = $this->uploadImage($request, 'flag', 'country/flags');
        if($obj = $this->_store($data)){
            return response()->json($this->_renderEdit($obj, 'Check_in successfully saved!'));
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

    public function update(CheckInRequest $request)
    {
        $request->validated();
        $id = decrypt($request->id);

        $data = $request->all();

        if($obj = $this->model->find($id)){
            $data['flag'] = $this->uploadImage($request, 'flag', 'Check/flags', $obj->flag);

            if($obj = $this->_update($obj, $data)){
                return response()->json($this->_renderEdit($obj, 'Check_In successfully updated!'));
            }
        }
        return response()->json(['error'=>'Oops!! something went wrong...Please try again.'], 422);
    }

     




    public function export(Request $request){

        if($request->check_ins_check_in_type_id == 1 || $request->check_ins_check_in_type_id == 4){
            $table_heads = ['Date', 'To(Location)','Consignor','Courier Agency Name', 'AWB No.', 'Date Sent','Zip Lock No.(As applicable)','Time of dispatch','Shipment handed over to(Name)','Description ','Check in Time','Check out Time'];
            $collection = $this->model->select(\DB::raw("date(entry_time)"),'location','consignor','courier_agency', 'awb_no', 'dated', 'zip_lock_no', 'time_of_receipt','shipment_handed_over_to','description_of_items',\DB::raw("time(entry_time)"),\DB::raw("time(exit_time)"))->where('check_in_type_id',$request->check_ins_check_in_type_id);
        }
        // Guard Logbook-Event Register
        elseif($request->check_ins_check_in_type_id == 5){
            $table_heads = ['Date','Time','Incident Brief','Name of Staff/Guard','informed to (Satff Name and Date of information)','Mode of informartion','Check in Time','Check out Time'];
            $collection = $this->model->select(\DB::raw("date(entry_time) as date"),\DB::raw("time(entry_time) as time"),'incident','name_of_staff','informed_to','mode',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 5);
        }
        // Material Outward Register
        elseif($request->check_ins_check_in_type_id == 6){
            $table_heads = ['Date','Time','Description of item (make, serial number)', 'Qty', 'Consignee (Full Name)','Gate pass number (if applicable)','Authorized by(If applicable as per the gate pass)','If returnable, expected date of return','Full Name and business address of recipient', 'Delivery Challan No.','Delivery Challan Date','Invoice No.','Invoice Date','Received_by','Check in Time','Check out Time'];
            $collection = $this->model->select(\DB::raw("date(entry_time)as date"),\DB::raw("time(entry_time)as time"),'description_of_items','qty','consignee','gate_pass_number','authorized','If_returnable_expected_date_of_return','full_name_and_address_of_recipient','delivery_challan_no','delivery_challan_date','invoice_no','invoice_Date','received_by',\DB::raw("time(entry_time)as check_in_time"),\DB::raw("time(exit_time)as checkout_time"))->where('check_in_type_id', 6);
        }
        // Material Inward Register
        elseif($request->check_ins_check_in_type_id == 7){
            $table_heads = ['Date','Time','Name of Vendor/supplier','Delivery Challan No.','Delivery Challan Date','Invoice No.','Invoice Date','Gate pass number (if applicable)','Description of Items','qty','Received by (Staff Name)','Check in Time','Check out Time'];
            $collection = $this->model->select(\DB::raw("date(entry_time) as date"),\DB::raw("time(entry_time) as time"),'name_of_vendor','delivery_challan_no','delivery_challan_date','invoice_no','invoice_Date','gate_pass_number','description_of_items', 'qty' ,'received_by',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time)as checkout_time"))->where('check_in_type_id', 7);
        }
        //   Key Register
        elseif($request->check_ins_check_in_type_id == 8){
            $table_heads = ['Date','Key','Number/Make','Number Held','Issued to (Name)','Position','Employee Number','Date of Issue','Date of return','Check in Time','Check out Time'];
            $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'register_key','register_number','register_number_held','register_issued','position','employee_number','date_of_issue','date_of_return',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 8);
        }
         //  Key Register Format-Passport & Cash Safes
         elseif($request->check_ins_check_in_type_id == 9){
            $table_heads = ['Date','Key 1 Staff Name','Key 2 Staff Name','Safe Opening Time','Safe Closing Time','Remarks','Check in Time','Check out Time'];
            $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'key_one_staff_name','key_two_staff_name','safe_opening_time','safe_closing_time','remarks',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 9);
        }
      //  Key Register Format-Key box
      elseif($request->check_ins_check_in_type_id == 10){
        $table_heads = ['Date','Key Box ,Key No.','Key Box Opening Time','Key Box Closing Time','Staff Name','Purpose','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'key_box','key_box_opening_time','key_box_closing_time','name_of_staff','purpose',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 10);
    }
      //VAC Opening Closing Register
      elseif($request->check_ins_check_in_type_id == 11){ 
        $table_heads = ['Date','Check in Time','Staff No. 1 Name ','Staff No. 2 Name','Check out Time','Staff No. 1 Name ','Staff No. 2 Name ','Name_of_Guard','Checklist','Remarks'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),\DB::raw("time(entry_time) as check_in_time"),'staff_one_name_check_in','staff_two_name_check_in' ,\DB::raw("time(exit_time) as checkout_time"),'staff_one_name_check_out','staff_two_name_check_out','guard','checklist','remarks')->where('check_in_type_id', 11);
    }
   //VAC CCTV Monitoring Log Sheet
     elseif($request->check_ins_check_in_type_id == 12){
       $table_heads = ['Date','Time','Details of Incident / Inappropriate behaviour observed','Location in the VAC','Reported to','Action Taken','Remarks (For closure)','Check in Time','Check out Time'];
       $collection = $this->model->select(\DB::raw("date(entry_time) as date"),\DB::raw("time(entry_time) as time"),'inappropriate_behaviour_observed','location_in_the_vac','reported_to','action_taken','remarks',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 12);
      }
         //Visitors Entry Exit Register
     elseif($request->check_ins_check_in_type_id == 13){
        $table_heads = ['Date','Name of the Visitor','Business address of visitor','National ID Type Checked(Passport,DL,etc.)*','Whom to Visit','Purpose of visit','Visitor Pass No.','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'name_of_the_visitor','business_address_of_visitor','national_id_type','whom_to_visit','purpose_of_visit','visitor_pass_no',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 13);
        
       }
            //Security Training Log for Staff
      elseif($request->check_ins_check_in_type_id == 14){
        $table_heads = ['Date','Employee Code','Name','Department / VAC','Facilitator','Medium of Training','Location','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'employ_code','secuirity_training_log_name','department','facilitator','medium_of_training','location',\DB::raw("time(entry_time)  as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 14);
        
       }

        //Security Training Log for Guards
      elseif($request->check_ins_check_in_type_id == 15){
        $table_heads = ['Date','ID No. / Code','Name','Guarding Agency Name /VFS Guard (in-house)','Department / VAC','Facilitator','Medium of Training','Location','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'id_code','guard','agency_name','department','facilitator','medium_of_training','location',\DB::raw("time(entry_time)  as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 15);
        
       }

       //Seal Control Log
       elseif($request->check_ins_check_in_type_id == 16){
        $table_heads = ['Date','Time','Seal Number / Ziplock number','Total Number of Passports / Documents','Name','Checked by','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),\DB::raw("time(entry_time)  as time"),'seal_number','total_number_of_passports','name','checked_by_manager',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 16);
       }

       //Fire  Evacuation Drill
        elseif($request->check_ins_check_in_type_id == 17){
        $table_heads = ['Date','Type','Weight','Location','Refilling Date','Expiry Date','Inspection Date','Next Due Date','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'type','weight','location','refillig_date','expiry_date','inspection_date','next_due_date',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 17);
       }
       //Fire Extinguisher Inspection Record Log
       elseif($request->check_ins_check_in_type_id == 18){
        $table_heads = ['Date','Type','Weight','Location','Refilling Date','Expiry Date','Inspection Date','Next Due Date','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'type','weight','location','refillig_date','expiry_date','inspection_date','next_due_date',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time) as checkout_time"))->where('check_in_type_id', 18);
       }
       //Mobile Phone Deposit
       elseif($request->check_ins_check_in_type_id == 19){
        $table_heads = ['Date','Name of Staff','Mobile Phone Unit & Model','Time Deposited','Check in Time','Check out Time'];
        $collection = $this->model->select(\DB::raw("date(entry_time) as date"),'name_of_staff','mobile_phone_unit_model','time_deposited',\DB::raw("time(entry_time) as check_in_time"),\DB::raw("time(exit_time)  as checkout_time"))->where('check_in_type_id', 19);
       }

        if(!empty($request->date_between)){
            $date_array = explode('-', $request->date_between);
            $from_date = $this->formatDate($date_array[0]);
            $from_date = date('Y-m-d H:i:s', strtotime($from_date.' 00:00:00'));
            $to_date = $this->formatDate($date_array[1]);
            $to_date = date('Y-m-d H:i:s', strtotime($to_date.' 00:00:00'));
            $collection->whereBetween('created_at', [$from_date, $to_date]);
        }

        $visitor_logs = $collection->take(1000)->get();
         // checkin_types obj formate  convert for excel sheet
         $register_types=Register_type::find($request->check_ins_check_in_type_id);
      
         $string_replace=preg_replace('/[^a-zA-z]/', '', $register_types->register_name);
         $checkin_types=strtolower($string_replace);
        //  return $checkin_types;exit;
         $excel_name = 'visitor_log_export_'.$checkin_types.round(microtime(true) * 1000).'.xlsx';
        $excelheadings = $register_types->register_name;
         return \Excel::download(new CheckinExport($visitor_logs,$table_heads,$excelheadings), $excel_name);
      
        //return (new CheckinExport($visitor_logs,$table_heads))->download($excel_name);
    }

}
