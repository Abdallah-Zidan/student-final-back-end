<?php

namespace App\Http\Resources;

use App\Enums\UserType;
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
            'address' => $this->address,
            'mobile' => $this->mobile,
            'avatar' => $this->avatar,
            'type' => $this->type,
            'verified' => $this->email_verified_at ? true : false,
        ] + ($this->type === UserType::getTypeString(UserType::COMPANY) ? ['profile' => new CompanyProfileResource($this->profile)] :
            ($this->type === UserType::getTypeString(UserType::STUDENT) ? ['profile' => new StudentProfileResource($this->profile)] : []));
    }
}
