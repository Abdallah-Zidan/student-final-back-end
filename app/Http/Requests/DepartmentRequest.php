<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
		if ($this->routeIs('dashboard.departments.store'))
		{
			return [
				'name' => 'required'
			];
		}
		else if ($this->routeIs('dashboard.departments.attach') || $this->routeIs('dashboard.departments.detach'))
		{
			return [
				'department_id' => 'required|integer',
				'faculty_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN)),
					'integer'
				]
			];
		}

		return [];
	}
}