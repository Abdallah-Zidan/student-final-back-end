<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacultyRequest extends FormRequest
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
		if ($this->routeIs('dashboard.faculties.store'))
		{
			return [
				'name' => 'required',
				'university_id' => 'required|integer'
			];
		}
		else if ($this->routeIs('dashboard.faculties.update'))
		{
			return [
				'university_id' => 'integer'
			];
		}

		return [];
	}
}