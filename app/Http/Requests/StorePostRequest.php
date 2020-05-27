<?php

namespace App\Http\Requests;

use App\Rules\PostScope;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
			'body' => 'required',
			'scope' => 'required|numeric|between:0,2',
			'scope_id' => 'required|numeric'
		];
	}
}