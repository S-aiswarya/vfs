<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Services\UserService;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ForgotPasswordSaveRequest;
use App\Http\Resources\Apis\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str, DB;
use App\Traits\ClientInfoTrait;

class UserAuthController extends Controller
{
    use ClientInfoTrait;
    public function login(UserLoginRequest $request){
        $request->validated();

        $user = User::where('email', $request->email)->where('user_type', 'user')->where('status', 1)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'invalid credentials'], 403);
        }
        
        $user->token = $user->createToken('auth_token', ['role:user', $this->role($user->role_id)])->plainTextToken;
        
         $ip=$this->get_ip();
         $checkin = new UserService();
         $checkin->saveCheckinHistory($ip, 'sign_in', $user->id);

        return new UserResource($user);
    }


      
     
    public function Sign_out(){
        $checkin = new UserService();
        $ip=$this->get_ip();
        $checkin->saveCheckinHistory($ip,'sign_out');

    }

     
     public function update(UserUpdateRequest $request, UserService $service){
        $request->validated();
        $item= User::find($request->id);
        if(!$item)
            return response()->json(['message' => 'Invalid Request'], 400);
        else
            return $service->update($item, $request);   
     }

   


    protected function role($id){
        $access_role = null;
        switch ($id) {
            case '3':
                $access_role = "role:super-admin";
                break;
            
            case '4':
                $access_role = "role:manager";
                break;

            case '5':
                $access_role = "role:sales";
                break;

        }
        return $access_role;
    }

    public function getUser(){
        $user = Auth::user();
        return new UserResource($user);
    }

    public function changePassword(ChangePasswordRequest $request){
        $request->validated();

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json(['message' => 'Your current password does not matches with the password you provided. Please try again.'], 400);
        }
        if(strcmp($request->get('current_password'), $request->get('new_pwd')) == 0){
            return response()->json(['message' => 'New Password cannot be same as your current password. Please choose a different password.'], 400);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->get('new_password'));
        $user->save();
        return response()->json(['message' => 'Password successfully changed.']);
    }

    public function forgot_password(ForgotPasswordRequest $request)
    {
        $request->validated();
		$data = $request->all();

		$user = User::where('email', $data['email'])->where('status', 1)->first();

        if(!$user)
            return response()->json(['message' => 'Sorry, this email address is not registered with us.'], 400);
			
		$token = Str::random(64);
		$otp = random_int(100000, 999999);
		DB::table('password_resets')->insert([
            'email' => $data['email'],
            'token' => $token,
            'otp' => $otp,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $mail = [];
        $mail['subject'] = "Reset Password - M & G Holidays";
        $mail['body'] = '<p>OTP (One Time Password) to reset your password for M & G Holidays is "'.$otp.'" and it is vaild for only 10 minutes.<br/>Do not share it with anyone for security reasons.</p>';

        \SpiderMailer::to($data['email'])->content($mail)->send();

		return response()->json(['message' => 'OTP has been successfully sent to your email address.']);
    }

    public function forgot_password_save(ForgotPasswordSaveRequest $request)
    {
        $request->validated();

        $updatePassword = DB::table('password_resets')->where(['email' => $request->email, 'otp' => $request->otp])->first();
    
        if(!$updatePassword){
            return response()->json(['message' => 'Invalid OTP'], 400);
        }
        
        $to_time = strtotime(date('Y-m-d H:i:s'));
        $from_time = strtotime($updatePassword->created_at);
        $time_diff =  round(abs($to_time - $from_time) / 60,2). " minute";
        
        if($time_diff>10)
            return response()->json(['message' => 'OTP expired'], 400);

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response()->json(['message' => 'New password has been successfully set.']);
    }
}
