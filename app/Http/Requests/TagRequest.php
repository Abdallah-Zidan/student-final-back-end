<?php

namespace App\Http\Requests;

use App\Enums\TagScope;
use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
		if ($this->routeIs('tags.index'))
		{
			return [
				'scope' => ['required', 'integer', 'between:0,' . (count(TagScope::$scopes) - 1)]
			];
		}
		else if ($this->routeIs('dashboard.tags.store'))
		{
			return [
				'name' => 'required'
			];
		}

		return [];
	}
}