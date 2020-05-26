<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexPostRequest extends FormRequest
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
			'department_faculty_id' => [Rule::requiredIf($this->user()->type != UserType::getTypeString(UserType::ADMIN)), 'numeric', 'exists:department_faculties,id'],
			'scope' => 'required|numeric|between:0,3'
		];
	}
}