<?php

namespace App\Http\Requests;

use App\Enums\PostScope;
use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
				'group' => 'required|integer|between:0,' . (count(PostScope::$scopes) - 1),
				'group_id' => 'required|integer'
			];
		}
		else if ($this->routeIs('posts.store'))
		{
			return [
				'group' => 'required|integer|between:0,' . (count(PostScope::$scopes) - 1),
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
		else if ($this->routeIs('dashboard.posts.store'))
		{
			return [
				'group' => 'required|integer|between:0,' . (count(PostScope::$scopes) - 1),
				'group_id' => 'required|integer',
				'body' => 'required',
				'year' => 'integer',
				'user_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN)),
					'integer'
				]
			];
		}
		else if ($this->routeIs('dashboard.posts.update'))
		{
			return [
				'group' => 'integer|between:0,' . (count(PostScope::$scopes) - 1),
				'group_id' => 'integer',
				'reported' => 'integer',
				'year' => 'integer',
				'user_id' => 'integer'
			];
		}

		return [];
	}
}