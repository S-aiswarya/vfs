<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class TargetGoalsController extends Controller
{
    public function index(Request $request){
        $data = $request->all();
        $user_id = ($request->counselor)?$request->counselor:auth()->user()->id;
        $user = User::where('status', 1)->where('id', $user_id)->first();
        if(!$user)
            return response()->json(['message' => 'Invalid Request'], 400);

        $target_leads = $user->targets->where('target_id', 1)->value('target_count');
        $target_applications = $user->targets->where('target_id', 3)->value('target_count');
        $target_students = $user->targets->where('target_id', 2)->value('target_count');
        $target_payments = $user->targets->where('target_id', 4)->value('target_count');

        $achived_leads = DB::table('leads')->where('created_by', $user_id);
        $from = $to = null;
        if(!empty($data['from']) && !empty($data['to']))
        {
            $from = date('Y-m-d H:i:s', strtotime($data['from']));
            $to = date('Y-m-d H:i:s', strtotime($data['to']));
        }
        if($from && $to)
            $achived_leads = $achived_leads->whereBetween('created_at', array($from, $to));
        $achived_leads = $achived_leads->count();

        $achived_applications = DB::table('applications')->where('created_by', $user_id);
        if($from && $to)
            $achived_applications = $achived_applications->whereBetween('created_at', array($from, $to));
        $achived_applications = $achived_applications->count();

        $achived_students = DB::table('students')->where('created_by', $user_id);
        if($from && $to)
            $achived_students = $achived_students->whereBetween('created_at', array($from, $to));
        $achived_students = $achived_students->count();

        $achived_payments = DB::table('payments')->where('created_by', $user_id);
        if($from && $to)
            $achived_payments = $achived_payments->whereBetween('created_at', array($from, $to));
        $achived_payments = $achived_payments->sum('amount');

        return response()->json(['data' => [
            'target_leads' => $target_leads,
            'target_applications' => $target_applications,
            'target_students' => $target_students,
            'target_payments' => $target_payments,
            'achived_leads' => $achived_leads,
            'achived_applications' => $achived_applications,
            'achived_students' => $achived_students,
            'achived_payments' => $achived_payments,
        ]]);
    }
}
