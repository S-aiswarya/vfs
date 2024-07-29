<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheckInRequest;
use App\Http\Resources\Apis\CheckinResource;
use App\Http\Resources\Apis\CheckinResourceCollection;
use App\Services\VisitorLogService;
use App\Models\CheckIn;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use DB;

class VisitorLogController extends Controller
{
    public function Checkin(CheckInRequest $request, VisitorLogService $service){
        $request->validated();
        return $service->store($request->all());
    }
}
