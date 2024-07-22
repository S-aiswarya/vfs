<?php 

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Services\MailSettings;
use App\Mail\Admin\RequestOtp;
use App\Traits\ClientInfoTrait;
use App\Events\LoginHistory;
use Auth;

class AuthenticateSessionOtpController extends Controller {

	use ClientInfoTrait;
	public function create($id=null)
	{
		$admin = new Admin;
        if($id){
            $id = decrypt($id);
            $admin = Admin::where('status', 1)->where('id', $id)->first();
        }
		return view('admin.auth.login')->with('admin', $admin);
	}

	public function request_otp(Request $request){
		$request->validate([
            'email' => 'required|max:190|email',
        ]);


        $admin = Admin::where('email', $request->email)->first();
		$redurect_url = route('admin.auth.login');

        if(!$admin)
            return redirect($redurect_url)->withError('This user is not registered with us.');
        else{
            if($admin->status == 0)
                return redirect($redurect_url)->withError('This account is suspended.');
            else{
                if($admin->account_blocked_on){
                    $account_blocked_on = new \DateTime($admin->account_blocked_on);
                    $now = new \DateTime("now");
                    $interval = abs($account_blocked_on->getTimestamp() - $now->getTimestamp()) / 60;
                    if($interval <= 10){
                        return redirect($redurect_url)->withError('This account is temporarily suspended due to suspicious activity.');
                    }
                    else{
                        $admin->opt_try_count = 0;
                        $admin->account_blocked_on = null;
                        $admin->account_blocked_ip = null;
                    }
                }

                $otp = $this->getOtp();
                $admin->otp = $otp;
                $admin->otp_sent_on = date('Y-m-d H:i:s');
                $admin->save();

                $mail = new MailSettings;
        		$mail->to($admin->email)->send(new RequestOtp($admin->name, $admin->otp));

                return redirect()->to(route('admin.auth.login', [encrypt($admin->id)]));
            }
        }
	}

	public function validate_otp(Request $request){
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $data = $request->all();
        $id = decrypt($data['id']);

        $admin = Admin::where('id', $id)->first();
		$redurect_url = route('admin.auth.login');
        if(!$admin)
            return redirect($redurect_url)->withError('This user is not registered with us.');

        if($admin->status == 0)
            return redirect($redurect_url)->withError('This account is suspended.');

        if($admin->opt_try_count == 3){
            $admin->account_blocked_on = date('Y-m-d H:i:s');
            $admin->account_blocked_ip = $this->get_ip();
            $admin->save();
            return redirect($redurect_url)->withError('This account is temporarily suspended due to suspicious activity.');
        }

        if($admin->otp != $data['otp']){
            $admin->opt_try_count = $admin->opt_try_count+1;
            $admin->save();
            return redirect()->back()->withError('Invalid Otp');
        }

        $otp_sent_on = new \DateTime($admin->login_otp_sent_on);
        $now = new \DateTime("now");
        $interval = abs($otp_sent_on->getTimestamp() - $now->getTimestamp()) / 60;
        if($interval > 10){
            return redirect('login')->withError('Otp expired.');
        }
        $admin->last_login_ip_address = $this->get_ip();
        $admin->save();

        Auth::guard('admin')->login($admin);
        event(new LoginHistory(['email'=>$admin->email], 'admin'));

        return redirect()->intended(route('admin.dashboard'));

    }

    public function resend_otp($id){
        $id = decrypt($id);
        $admin = Admin::where('id', $id)->first();

        if(!$admin)
            return response()->json(['errors'=>true, 'message'=>'This user is not registered with us.']);
        else{
            if($admin->status == 0)
                return response()->json(['errors'=>true, 'message'=>'This account is suspended.']);
            else{
                if($admin->account_blocked_on){
                    $account_blocked_on = new \DateTime($admin->account_blocked_on);
                    $now = new \DateTime("now");
                    $interval = abs($account_blocked_on->getTimestamp() - $now->getTimestamp()) / 60;
                    if($interval <= 10){
                        return response()->json(['errors'=>true, 'message'=>'This account is temporarily suspended due to suspicious activity.']);
                    }
                }

                $otp = $this->getOtp();
                $admin->otp = $otp;
                $admin->otp_sent_on = date('Y-m-d H:i:s');
                $admin->save();
				
                $mail = new MailSettings;
        		$mail->to($admin->email)->send(new RequestOtp($admin->name, $admin->otp));

                return response()->json(['success' => true]);
            }
        }
    }

    protected function getOtp(){
        if(config('app.env') == "production") {
            return rand(111111,999999);
        }
        else
            return "123456";
    }

	public function logout(Request $request){
		Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('admin.auth.login'));
	}

}
