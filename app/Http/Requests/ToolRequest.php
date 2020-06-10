<?php

namespace App\Http\Requests;

use App\Enums\ToolType;
use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
				'type' => 'required|integer|between:0,' . (count(ToolType::$types) - 1),
				'tags' => 'string'
			];
		}
		else if ($this->routeIs('tools.store'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'type' => 'required|integer|between:0,' . (count(ToolType::$types) - 1),
				'tags' => 'required|array',
				'tags.*' => 'required|distinct',
				'files' => 'array',
				'files.*' => 'file|max:51200'
			];
		}
		else if ($this->routeIs('tools.update'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'tags' => 'required|array',
				'tags.*' => 'required|distinct'
			];
		}
		else if ($this->routeIs('tools.close'))
		{
			return [
				'id' => 'required|integer'
			];
		}
		else if ($this->routeIs('dashboard.tools.index'))
		{
			return [
				'type' => 'integer|between:0,' . (count(ToolType::$types) - 1),
				'tags' => 'string'
			];
		}
		else if ($this->routeIs('dashboard.tools.store'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'type' => 'required|integer|between:0,' . (count(ToolType::$types) - 1),
				'faculty_id' => 'required|integer',
				'user_id' => 'required|integer'
			];
		}
		else if ($this->routeIs('dashboard.tools.update'))
		{
			return [
				'type' => 'integer|between:0,' . (count(ToolType::$types) - 1),
				'closed' => 'integer',
				'faculty_id' => 'integer',
				'user_id' => 'integer'
			];
		}
		else if ($this->routeIs('dashboard.tools.attach') || $this->routeIs('dashboard.tools.detach'))
		{
			return [
				'tool_id' => 'required|integer',
				'tag_id' => 'required|integer'
			];
		}

		return [];
	}
}