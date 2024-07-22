@extends('email._layouts.user_base')

@section('content')
<div style=" padding: 25px; margin: 30px 30px 0;">
    {!! $data['body'] !!}
</div>
@if(!empty($data['body_footer']) && strip_tags($data['body_footer']) != "")
<div style=" padding: 25px; margin: 30px 30px 0; background-color: #236342; color: #fff; text-align: center; ">
    {!! $data['body_footer'] !!}
</div>

@endif

@endsection