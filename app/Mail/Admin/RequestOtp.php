<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class RequestOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public string $admin, public string $otp)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $site_name = Setting::where('code', 'site_name')->pluck('value_text');
        $subject = "{$this->otp} is the OTP for your {$site_name} login request";
        $send = $this->subject($subject)->view('admin.email.request_otp');
        return $send;
    }
}
