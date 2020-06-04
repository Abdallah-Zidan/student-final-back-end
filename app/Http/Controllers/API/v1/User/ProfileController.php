<?php

namespace App\Http\Controllers\API\v1\User;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(ProfileRequest $request)
    {
        if ($request->new_password) // change password
        {
            return $this->changePassword($request);
        }
        if ($request->hasFile('avatar')) {
            $this->saveAvatar($request);
        }

        $user = $request->user();
        $user->update($request->only(['name', 'address', 'mobile']));

        if ($user->type === UserType::getTypeString(UserType::STUDENT)) {
            $user->profileable()->update($request->only(['birthdate']));
        } else if ($user->type === UserType::getTypeString(UserType::COMPANY)) {
            $user->profileable()->update($request->only(['fax', 'description', 'website']));
        } else if ($user->type === UserType::getTypeString(UserType::TEACHING_STAFF)) {
            $user->profileable()->update($request->only(['birthdate', 'scientific_certificates']));
        }

        return new UserResource($user);
    }

    private function saveAvatar(Request $request)
    {
        $user = $request->user();
        //delete old avatar
        Storage::disk('local')->delete($user->avatar);
        //save new avatar
        $user->avatar = Storage::disk('local')->put('images/users', $request->avatar);
        $user->save();
    }

    private function changePassword(ProfileRequest $request)
    {
        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            $user->update(['password' => $request->new_password]);
            return response([], 204);
        } else {
            return response(['Message' => 'Wrong Password'], 422);
        }
    }
}
