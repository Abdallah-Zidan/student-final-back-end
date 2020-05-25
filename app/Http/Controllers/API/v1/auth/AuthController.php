<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\CompanyProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([],422);
        }
        if ($token = $user->tokens()->where('name', $request->device_name)) {
            $token->delete();
        }
        $token = $user->createToken($request->device_name);
        $response_data['data']['token']['access_token'] = $token->plainTextToken;
        $response_data['data']['token']['expired_at'] = $this->get_token_expired_time($token);
        $response_data['data']['user'] = new UserResource($user);

        return response([$response_data]);
    }

    private function get_token_expired_time(NewAccessToken $token)
    {
        return Carbon::parse($token->accessToken->create_at)
            ->addMinutes(config('sanctum.expiration'))
            ->toDateTimeString();
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
                    'mobile',
                    'gender'
                ]
            ) + [
                'type' => User::getTypeFromValue($request->type),
                'avatar' =>  'image/users' . ($request->gender  == 0 ? 'default_male.png' : 'default_female.png')
            ]
        );

        if ($request->type == 0)  // student
            $user->profile()->create($request->only(['birthdate', 'year']));
        else                    // company
            $user->profile()->create($request->only(['fax', 'description', 'website']));

        $token = $user->createToken($request->device_name);
        $response_data['data']['token']['access_token'] = $token->plainTextToken;
        $response_data['data']['token']['expired_at'] = $this->get_token_expired_time($token);
        $response_data['data']['user'] = new UserResource($user);

        $user->sendEmailVerificationNotification();

        return response($response_data, 200);
    }

    public function logout(Request $request)
    {
        if ($token = $request->user()->tokens()->where('name', $request->device_name)) {
            $token->delete();
            return response([]);
        }
        return response([], 402);
    }
}
