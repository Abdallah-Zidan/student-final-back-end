<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
			'title' => 'required',
			'body' => 'required',
			'type' => 'required|between:0,4',
			'start_date' => 'date',
			'end_date' => 'date|after_or_equal:start_date'
		] + ($this->isMethod('post') ? [
			'files' => 'array',
			'files.*' => 'file|max:51200'
		] : []);
	}
}