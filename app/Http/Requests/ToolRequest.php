<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToolRequest extends FormRequest
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
		if ($this->routeIs('tools.index'))
		{
			return [
				'type' => 'required|integer|between:0,1',
				'tags' => 'string'
			];
		}
		else if ($this->routeIs('tools.store'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'type' => 'required|integer|between:0,1',
				'tags' => 'string',
				'files' => 'array',
				'files.*' => 'file|max:51200'
			];
		}
		else if ($this->routeIs('tools.update'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'tags' => 'string'
			];
		}
	}
}