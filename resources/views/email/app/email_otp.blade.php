@extends('email.app.email_base')
@section('body')
    <!-- module 6 -->
<table data-module="module-6" data-thumb="thumbnails/06.png" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
    <tr>
        <td data-bgcolor="bg-module" bgcolor="#eaeced">
            <table class="flexible" width="600" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
                <tr>
                    <td data-bgcolor="bg-block" class="holder" style="padding:64px 60px 50px;" bgcolor="#f9f9f9">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:0 0 20px;">
                                    <table width="232" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td  align="center">
                                                <img src="{{asset('admin/assets/images/logo.png')}}" alt="" width="150px">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td data-color="title" data-size="size title" data-min="20" data-max="40" data-link-color="link title color" data-link-style="text-decoration:none; color:#292c34;" class="title" align="center" style="font:30px/33px Arial, Helvetica, sans-serif; color:#292c34; padding:0 0 23px;">
                                    {{$otp}}
                                </td>
                            </tr>
                            <tr>
                                <td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:bold; text-decoration:underline; color:#40aceb;" align="center" style="font:12px/16px Arial, Helvetica, sans-serif;  padding:0 0 21px;">
                                    This is the OTP to access KCPMC customer application. Do not share it with anyone.
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr><td height="28"></td></tr>
            </table>
        </td>
    </tr>
</table>
<!-- module 7 -->
@endsection