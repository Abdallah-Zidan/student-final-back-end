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
		if ($this->routeIs('events.index'))
		{
			return [
				'group' => 'required|integer|between:0,2',
				'group_id' => 'required_unless:group,2|integer',
				'type' => 'required|integer|between:0,4'
			];
		}
		else if ($this->routeIs('events.store'))
		{
			return [
				'group' => 'required|integer|between:0,2',
				'group_id' => 'required|integer',
				'title' => 'required',
				'body' => 'required',
				'type' => 'required|between:0,4',
				'start_date' => 'date',
				'end_date' => 'date|after_or_equal:start_date',
				'files' => 'array',
				'files.*' => 'file|max:51200'
			];
		}
		else if ($this->routeIs('events.update'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'start_date' => 'date',
				'end_date' => 'date|after_or_equal:start_date'
			];
		}
	}
}