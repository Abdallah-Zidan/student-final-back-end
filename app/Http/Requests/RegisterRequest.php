<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Rules\EducationEmail;
use App\Rules\FacultyDepartmentsExistsRule;
use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|max:255',
            'password' => ['required', new StrongPassword()],
            'gender' => 'required|in:0,1',
            'address' => 'required|max:255',
            'mobile' => 'required|unique:users|max:15|min:11',
            'type' => 'required|in:0,1',
            'device_name' => 'required'
        ] + $this->getUserTypeRules();
    }



    private function getUserTypeRules()
    {
        if ($this->type == UserType::COMPANY) {
            return [
                'email' => 'required|email|unique:users|max:255',
                'fax' => 'required|unique:App\CompanyProfile|max:15|min:11',
                'description' => 'required|max:255',
                'website' => 'required|unique:company_profiles|max:255|url'
            ];
        } else if ($this->type == UserType::STUDENT) {
            return [
                'email' => ['required', 'unique:users', 'max:255', new EducationEmail()],
                'birthdate' => 'required|date|before:today',
                'year' => 'required|lte:7|gt:0',
                'departments' => 'required|array|max:3',
                'departments.*' => ['required', 'exists:departments,id', 'distinct', new FacultyDepartmentsExistsRule($this->faculty)],
                'faculty' => 'required|exists:faculties,id',
            ];
        }
    }
}
