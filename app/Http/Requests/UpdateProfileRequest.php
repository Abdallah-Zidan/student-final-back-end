<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255',
            'new_password' => [new StrongPassword(), Rule::requiredIf($this->password)],
            'address' => 'max:255',
            'mobile' => ['max:15', 'min:11', Rule::unique('users')->ignore($this->user())],
            'avatar' => 'mimes:jpeg,bmp,png|file|max:2048'
        ] + ($this->user()->type == UserType::getTypeString(UserType::COMPANY) ? [
            'fax' => ['max:15', 'min:11', Rule::unique('company_profiles')->ignore($this->user()->profileable)],
            'description' => 'max:255',
            'website' => ['max:255', 'url', Rule::unique('company_profiles')->ignore($this->user()->profileable)]
        ] : ($this->user()->type == UserType::getTypeString(UserType::STUDENT)
            || $this->user()->type == UserType::getTypeString(UserType::TEACHING_STAFF) ? [
                'birthdate' => 'date|before:today'
            ] : []));
    }
}
