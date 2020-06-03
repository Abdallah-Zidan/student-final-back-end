<?php

namespace App\Http\Resources;

use App\Enums\UserType;
use App\StudentProfile;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'verified' => $this->email_verified_at ? true : false,
            'gender' => $this->gender,
            'blocked' => $this->blocked,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'avatar' => $this->avatar,
            'type' => $this->type
        ] + $this->getProfile();
    }

    public function getProfile()
    {
        if ($this->type == UserType::getTypeString(UserType::STUDENT)) {
            return ['profile' => new StudentProfileResource($this->profileable)];
        } else if ($this->type == UserType::getTypeString(UserType::COMPANY)) {
            return ['profile' => new CompanyProfileResource($this->profileable)];
        } else if ($this->type == UserType::getTypeString(UserType::TEACHING_STAFF)) {
            return ['profile' => new TeachingStaffProfileResource($this->profileable)];
        }
        return [];
    }
}
