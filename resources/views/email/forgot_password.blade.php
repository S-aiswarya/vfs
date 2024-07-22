@extends('email._layouts.user_base')

@section('content')
<div style=" padding: 25px; margin: 30px 30px 0;">
    <h1>Reset Password</h1>
    <p>OTP (One Time Password) to reset your password for M & G is "'.$otp.'" and it is vaild for only 10 minutes.<br/>Do not share it with anyone for security reasons.</p>
</div>
@endsection