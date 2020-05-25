<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
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

    public function update(UpdateProfileRequest $request)
    {
        if ($request->new_password) // change password
        {
            return $this->change_password($request);
        }
        if ($request->hasFile('avatar')) {
            $this->save_avatar($request);
        }

        $user = $request->user();
        $user->update($request->only(['name', 'address', 'mobile']));

        if ($user->type == 'Student') {
            $user->profile()->update($request->only(['birthdate']));
        } else if ($user->type == 'Company') {
            $user->profile()->update($request->only(['fax', 'description', 'website']));
        }

        return new UserResource($user);
    }

    private function save_avatar(Request $request)
    {
        $user = $request->user();
        //delete old avatar
        Storage::disk('local')->delete($user->avatar);
        //save new avatar
        $user->avatar = Storage::disk('local')->put('images/users', $request->avatar);
        $user->save();
    }

    private function change_password(UpdateProfileRequest $request)
    {
        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            $user->update(['password' => $request->new_password]);
            return response([], 204);
        } else {
            return response([],422);
        }
    }
}
