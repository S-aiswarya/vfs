<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentLoginRequest;
use App\Http\Resources\Apis\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TravellerAuthController extends Controller
{
    public function login(StudentLoginRequest $request){
        $request->validated();

        $user = User::where('email', $request->email)->where('user_type', 'traveller')->where('status', 1)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'invalid credentials'], 403);
        }

        $user->token = $user->createToken('auth_token', ['role:traveller'])->plainTextToken;

        return new UserResource($user);
    }

}
