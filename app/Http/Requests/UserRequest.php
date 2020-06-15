<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
		if ($this->routeIs('dashboard.users.store'))
		{
			return [
				'name' => 'required',
				'email' => 'required|unique:users,email',
				'password' => 'required',
				'gender' => 'required|integer|between:0,1',
				'address' => 'required',
				'mobile' => 'required|unique:users,mobile',
				'avatar' => 'mimes:jpeg,bmp,png|file|max:2048',
				'type' => 'required|integer|between:0,' . (count(UserType::$types) - 1),
				'birthdate' => [
					'required_if:type,' . UserType::STUDENT,
					'required_if:type,' . UserType::TEACHING_STAFF,
					'date',
					'before:today'
				],
				'year' => [
					'required_if:type,' . UserType::STUDENT,
					'integer',
					'between:1,7'
				],
				'scientific_certificates' => 'required_if:type,' . UserType::TEACHING_STAFF,
				'fax' => [
					'required_if:type,' . UserType::COMPANY,
					'unique:company_profiles,fax'
				],
				'description' => 'required_if:type,' . UserType::COMPANY,
				'website' => [
					'required_if:type,' . UserType::COMPANY,
					'unique:company_profiles,website',
					'url'
				],
				'faculty_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN) && $this->has('type') && $this->type == UserType::MODERATOR),
					'integer'
				],
				'department_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::MODERATOR) && $this->has('type') && $this->type == UserType::TEACHING_STAFF),
					'integer'
				]
			];
		}
		else if ($this->routeIs('dashboard.users.update'))
		{
			return [
				'email' => 'unique:users,email,' . $this->user->id,
				'gender' => 'integer|between:0,1',
				'blocked' => 'integer|between:0,1',
				'mobile' => 'unique:users,mobile,' . $this->user->id,
				'avatar' => 'mimes:jpeg,bmp,png|file|max:2048',
				'birthdate' => 'date|before:today',
				'year' => 'integer|between:1,7',
				'faculty_id' => 'integer'
			] + ($this->user->type === UserType::getTypeString(UserType::COMPANY) ? [
				'fax' => 'unique:company_profiles,fax,' . $this->user->profileable->id,
				'website' => [
					'unique:company_profiles,website,' . $this->user->profileable->id,
					'url'
				]
			] : []);
		}
		else if ($this->routeIs('dashboard.users.departments.attach') || $this->routeIs('dashboard.users.departments.detach'))
		{
			return [
				'user_id' => 'required|integer',
				'department_id' => 'required|integer',
				'faculty_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN)),
					'integer'
				]
			];
		}
		else if ($this->routeIs('dashboard.users.courses.attach') || $this->routeIs('dashboard.users.courses.detach'))
		{
			return [
				'user_id' => 'required|integer',
				'course_id' => 'required|integer',
				'department_id' => 'required|integer',
				'faculty_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN)),
					'integer'
				]
			];
		}
	}
}