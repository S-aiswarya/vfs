<?php

namespace App\Listeners;

use App\Events\LoginHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\ClientInfoTrait;
use DB;

class storeUserLoginHistory
{
    use ClientInfoTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginHistory  $event
     * @return void
     */
    public function handle(LoginHistory $event)
    {
        $data = $event->login_data;
        $input = [];
        $input['users_id'] = null;
        if($user = auth()->guard($event->user_guard)->user())
        {
            $input['users_id'] = $user->id;
        }
        $input['email'] = $data['email'];
        $input['ip_address'] = $this->get_ip();
        $browser_details = $this->get_browser();
        $input['user_agent'] = $browser_details['userAgent'];
        DB::table('login_history')->insert($input);
        return true;
    }
}
