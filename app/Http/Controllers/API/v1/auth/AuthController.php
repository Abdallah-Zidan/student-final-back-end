<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\CompanyProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\StudentProfile;
use App\TeachingStaffProfile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['The provided credentials are incorrect.'],
            ]);
        }
        if ($token = $user->tokens()->where('name', $request->device_name)) {
            $token->delete();
        }
        $token = $user->createToken($request->device_name)->plainTextToken;
        $response_data['data']['token'] = $token;
        $response_data['data']['user']=new UserResource($user);
        $response_data['message'] = 'login successful';
        return response()->json($response_data, 200);
    }



    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only(
                [
                    'name',
                    'email',
                    'password',
                    'address',
                    'mobile'
                ]
            ) + ['type' => User::getTypeFromValue($request->type)]
        );

        if ($request->type == 0)  // student
            $user->profile()->create($request->only(['birthdate', 'year']));
        else                    // company
            $user->profile()->create($request->only(['fax', 'description', 'website']));

        $token = $user->createToken($request->device_name)->plainTextToken;
        $response_data['data']['token'] = $token;
        $response_data['message'] = 'register successful';
        $response_data['data']['user']=new UserResource($user);
        $user->sendEmailVerificationNotification();
        return response()->json($response_data, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'logout successful'], 200);
    }
}
