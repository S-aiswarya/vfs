<?php

namespace App\Http\Controllers\Admin;

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
        // Courier Inward Register
        if($request->check_ins_check_in_type_id == 1){
            $table_heads = ['Date', 'From(Location)', 'Consignor','Courier Agency Name', 'AWB No.', 'Date Sent','Zip Lock No.(As applicable)','Time of receipt','Shipment handed over to(Name)','Check in Time','Check out Time'];
            $collection = $this->model->select('DB::raw("date(entry_time)")','location','consignor','courier_agency', 'awb_no', 'dated', 'zip_lock_no', 'time_of_receipt','shipment_handed_over_to', 'DB::raw("time(entry_time)")")','DB::raw("time(exit_time)")' );
        }
        // Courier Outward
        elseif($request->check_ins_check_in_type_id == 4 or  1){
            $table_heads = ['Date', 'To(Location)', 'Consignor','Courier Agency Name', 'AWB No.', 'Date Sent','Zip Lock No.(As applicable)','Time of dispatch','Shipment handed over to(Name)','Check in Time','Check out Time'];
            $collection = $this->model->select('DB::raw("date(entry_time)")','location','consignor','courier_agency', 'awb_no', 'dated', 'zip_lock_no', 'time_of_receipt','shipment_handed_over_to','DB::raw("time(entry_time)")','DB::raw("time(exit_time)")');
        }
        // Guard Logbook-Event Register
        elseif($request->check_ins_check_in_type_id == 5){
            $table_heads = ['Date','Time', 'Incident Brief', 'Name of Staff/Guard','informed to (Satff Name and Date of information)','Mode of informartion','Check in Time','Check out Time'];
            $collection = $this->model->select('DB::raw("date(entry_time)")','DB::raw("time(entry_time)")','incident','name_of_staff', 'informed_to','mode','DB::raw("time(entry_time)")','DB::raw("time(exit_time)")');
        }
        // Material Outward Register
        elseif($request->check_ins_check_in_type_id == 6){
            $table_heads = ['Date','Time', 'Description of item (make, serial number)', 'Qty', 'Consignee (Full Name)','Gate pass number (if applicable)','Authorized by(If applicable as per the gate pass)','If returnable, expected date of return','Full Name and business address of recipient', 'Delivery Challan No.','Delivery Challan Date','Invoice No.','Invoice Date','Received_by','Check in Time','Check out Time'];
            $collection = $this->model->select('DB::raw("date(entry_time)")','DB::raw("time(entry_time)")','description_of_items','qty','consignee','gate_pass_number','authorized', 'If_returnable_expected_date_of_return' ,'full_name_and_address_of_recipient','delivery_challan_no','dated','invoice_no','Dated','received_by', 'DB::raw("time(entry_time)")','DB::raw("time(exit_time)")');///
        }
        // Material Inward Register
        elseif($request->check_ins_check_in_type_id == 7){
            $table_heads = ['Date','Time', 'Name of Vendor/supplier','Delivery Receipt/ Invoice No./Date','Gate pass number (if applicable)','Description of Items','qty','Received by (Staff Name)',];
            $collection = $this->model->select('DB::raw("date(entry_time)")','DB::raw("time(entry_time)")','name_of_vendor','invoice_no','gate_pass_number','description_of_items', 'qty' ,'received_by',);
        }
        //   Key Register
        elseif($request->check_ins_check_in_type_id == 8){
            $table_heads = ['Date', 'Key','Number/Make','Issued to (Name)','Position','Date of Issue','Date of return','Signature of recipient','Check in Time','Check out Time'];
            $collection = $this->model->select('DB::raw("date(entry_time)")','register_key','register_number','register_issued','position', 'date_of_issue' ,'date_of_return','recipient_signature','entry_time','exit_time');
        }
         //  Key Register Format-Passport & Cash Safes
         elseif($request->check_ins_check_in_type_id == 9){
            $table_heads = ['Date','Key 1 Staff Name','Key 2 Staff Name','Safe Opening Time','Safe Closing Time','Key 1 Staff Signature','Key 2 Staff Signature','Remarks','Check in Time','Check out Time'];
            $collection = $this->model->select('DB::raw("date(entry_time)")','key_one_staff_name','key_two_staff_name','safe_opening_time','safe_closing_time','key_one_staff_signature', 'key_two_staff_signature' ,'remarks','entry_time','exit_time');
        }
      //  Key Register Format-Key box
      elseif($request->check_ins_check_in_type_id == 10){
        $table_heads = ['Date','Key Box Key No.','Key Box Opening Time','Key Box Closing Time','Staff Name','Staff Signature','Purpose','DM Signature','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','key_box','key_box_opening_time','key_box_closing_time','name_of_staff','staff_signature', 'purpose' ,'dm_signature','entry_time','exit_time');
    }
      //VAC Opening Closing Register
      elseif($request->check_ins_check_in_type_id == 11){ 
        $table_heads = ['Date','Time In','Staff No. 1 Name & Signature','Staff No. 2 Name & Signature','Time Out','Staff No. 1 Name & Signature','Staff No. 2 Name & Signature','Remarks','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','DB::raw("time(entry_time)")','staff_one_name_signature','staff_two_name_signature','DB::raw("time(exit_time)")','staff_one_name_signature', 'staff_two_name_signature','remarks','entry_time','exit_time');
    }
   //VAC CCTV Monitoring Log Sheet
     elseif($request->check_ins_check_in_type_id == 12){
       $table_heads = ['Date','Time','Details of Incident / Inappropriate behaviour observed','Location in the VAC','Reported to','Action Taken','Remarks (For closure)','Check in Time','Check out Time'];
       $collection = $this->model->select('DB::raw("date(entry_time)")','DB::raw("time(entry_time)")','inappropriate_behaviour_observed','location_in_the_vac','reported_to','action_taken', 'remarks(for closure)','entry_time','exit_time');
      }
         //Visitors Entry Exit Register
     elseif($request->check_ins_check_in_type_id == 13){
        $table_heads = ['Date','Name of the Visitor','Business address of visitor','National ID Type Checked(Passport,DL,etc.)*','Whom to Visit','Purpose of visit','Visitor Pass No.','Time In','Signature of visitor','Time out','Signature of security guard','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','name_of_the_visitor','business_address_of_visitor','national_id_type','whom_to_visit','purpose_of_visit','visitor_pass_no','DB::raw("time(entry_time)")','signature_of_visitor','DB::raw("time(exit_time)")','signature_of_security_guard','entry_time','exit_time');
        
       }
            //Security Training Log for Staff
      elseif($request->check_ins_check_in_type_id == 14){
        $table_heads = ['Date','Employee Code','Name','Department / VAC','Facilitator','Medium of Training','Location','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','employ_code','secuirity_training_log_name','department','facilitator','medium_of_training','location','entry_time','exit_time');
        
       }

        //Security Training Log for Guards
      elseif($request->check_ins_check_in_type_id == 15){
        $table_heads = ['Date','ID No. / Code','Guarding Agency Name /VFS Guard (in-house)','Employee Code','Name','Department / VAC','Facilitator','Medium of Training','Location','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','id_code','agency_name','employ_code','secuirity_training_log_name','department','facilitator','medium_of_training','location','entry_time','exit_time');
        
       }

       //Seal Control Log
       elseif($request->check_ins_check_in_type_id == 16){
        $table_heads = ['Date','Time','Seal Number / Ziplock number','Total Number of Passports / Documents','Name and Sign of Dispatcher','Checked by Manager / Supervisor (Name and Sign)','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','DB::raw("time(entry_time)")','seal_number','total_number_of_passports','sign_of_dispatcher','checked_by_manager','entry_time','exit_time');
       }

       //Fire / Evacuation Drill
         elseif($request->check_ins_check_in_type_id == 17){
        $table_heads = ['Date','Type','Weight','Location','Refilling Date','Expiry Date','Inspection Date','Next Due Date','Signature','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','type','weight','location','refillig_date','expiry_date','inspection_date','next_due_date','signature','entry_time','exit_time');
       }
       //Fire Extinguisher Inspection Record Log
       elseif($request->check_ins_check_in_type_id == 18){
        $table_heads = ['Date','Type','Weight','Location','Refilling Date','Expiry Date','Inspection Date','Next Due Date','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','type','weight','location','refillig_date','expiry_date','inspection_date','next_due_date','entry_time','exit_time');
       }
       //Mobile Phone Deposit
       elseif($request->check_ins_check_in_type_id == 19){
        $table_heads = ['Date','Name of Staff','Mobile Phone Unit & Model','Time Deposited','Check in Time','Check out Time'];
        $collection = $this->model->select('DB::raw("date(entry_time)")','name_of_staff','mobile_phone_unit_model','time_deposited','entry_time','exit_time');
       }

       
        if(request()->get('data'))
        {
            $collection = $this->applyFiltering($collection);
        }
        else
            $collection->where('status', 'Open');
        $leads = $collection->take(1000)->get();

        foreach($leads as $lead){
            if(!empty($lead->extra_data)){
                $extra_data = json_decode($lead->extra_data, true);
                foreach($extra_data as $key=>$eData){
                    $lead->$key = $eData;
                }
                unset($lead->extra_data);
            }
            else{
                unset($lead->extra_data);
            }
            $lead->created_at = date('d-m-Y H:i:s', strtotime($lead->created_at));
        }
        $table_heads[] = 'UTM Source';
        $table_heads[] = 'Source Url';
        $table_heads[] = 'Status';
        $table_heads[] = 'Created On';

        $excel_name = 'lead_export_'.round(microtime(true) * 1000).'.xlsx';
        return (new LeadExport($leads, $table_heads))->download($excel_name);
    }

}
