<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserVerify;
use App\Http\Resources\VerifiedUser;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\MailSettings;
use App\Services\SMS\Greenads;

class AuthController extends Controller
{
    public function verify_user(Request $request)
    {
        if(empty($request->user))
            return response()->json(['status' => 'error', 'error' => 'Missing parameters'], 400);
        else{

            $username = $request->user;
            $login_type = '';
            if (filter_var($username, FILTER_VALIDATE_EMAIL))
            {
                $login_type = 'Email';
                $customer = Customer::where('email', $username)->first();
            }
            else
            {
                $login_type = 'Phone';
                $customer = Customer::where('phone_number', $username)->first();
            }
            if(!$customer)
                return response()->json(['status' => 'error', 'error' => 'Sorry! you do not have access to this App'], 400);
            else{
                if($customer->status == 0 || $customer->has_api_access == 0)
                    return response()->json(['status' => 'error', 'error' => 'Sorry! you do not have access to this App'], 400);
                else{

                    if($customer->account_blocked_on){
                        $account_blocked_on = new \DateTime($customer->account_blocked_on);
                        $now = new \DateTime("now");
                        $interval = abs($account_blocked_on->getTimestamp() - $now->getTimestamp()) / 60;
                        if($interval <= 10){
                            return response()->json(['status' => 'error', 'error' => 'This account is temporarily suspended due to suspicious activity.'], 400);
                        }
                        else{
                            $customer->opt_try_count = 0;
                            $customer->account_blocked_on = null;
                        }
                    }

                    $otp = rand(111111,999999);
                    $customer->otp = $otp;
                    $customer->save();
                    if($login_type == 'Email')
                    {
                        $mail = new MailSettings;
        		        $mail->to($username)->send(new \App\Mail\SendOtp($otp));
                    }
                    else{
                        $message = "Hi ".$customer->name.", ".$otp." is the OTP to access KCPMC sales application. Do not share it with anyone.";
                        $sms = new Greenads($customer->phone_number, $message);
                        $sms->sendSms();
                    }
                    return new UserVerify($customer);
                }
            }
        }
    }

    public function verify_otp(Request $request)
    {

        if(empty($request->otp) || empty($request->user_id))
            return response()->json(['status' => 'error', 'error' => 'Missing parameters'], 400);
        else
        {
            $id = $request->user_id;
            $otp = $request->otp;
            $customer = Customer::where('id', $id)->where('status', 1)->where('has_api_access', 1)->first();
            if(!$customer)
                return response()->json(['status' => 'error', 'error' => 'Invalid OTP'], 400);
            else{

                if($customer->opt_try_count == 3){
                    $customer->account_blocked_on = date('Y-m-d H:i:s');
                    $customer->save();
                    return response()->json(['status' => 'error', 'error' => 'This account is temporarily suspended due to suspicious activity.'], 400);
                }
        
                if($customer->otp != $otp){
                    $customer->opt_try_count = $customer->opt_try_count+1;
                    $customer->save();
                    return response()->json(['status' => 'error', 'error' => 'Invalid Otp'], 400);
                }

                $otp_sent_on = new \DateTime($customer->login_otp_sent_on);
                $now = new \DateTime("now");
                $interval = abs($otp_sent_on->getTimestamp() - $now->getTimestamp()) / 60;
                if($interval > 10){
                    return response()->json(['status' => 'error', 'error' => 'Otp expired'], 400);
                }

                $new_token = $customer->createToken('auth_token')->plainTextToken;
                $customer->auth_token = $new_token;
                return new VerifiedUser($customer);
            }
        }
    }
}
