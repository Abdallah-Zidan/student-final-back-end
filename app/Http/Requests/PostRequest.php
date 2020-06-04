<?php

namespace App\Http\Requests;

use App\Enums\PostScope;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
		if ($this->routeIs('posts.index'))
		{
			return [
				'group' => 'required|integer|between:0,' . count(PostScope::$scopes) - 1,
				'group_id' => 'required|integer'
			];
		}
		else if ($this->routeIs('posts.store'))
		{
			return [
				'group' => 'required|integer|between:0,' . count(PostScope::$scopes) - 1,
				'group_id' => 'required|integer',
				'body' => 'required',
				'files' => 'array',
				'files.*' => 'file|max:51200'
			];
		}
		else if ($this->routeIs('posts.update'))
		{
			return [
				'body' => 'required'
			];
		}
		else if ($this->routeIs('posts.report'))
		{
			return [
				'id' => 'required|integer'
			];
		}
	}
}