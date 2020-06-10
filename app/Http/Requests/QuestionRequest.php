<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
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
		if ($this->routeIs('questions.index'))
		{
			return [
				'tags' => 'string'
			];
		}
		else if ($this->routeIs('questions.store') || $this->routeIs('questions.update'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'tags' => 'required|array',
				'tags.*' => 'required|distinct'
			];
		}
		else if ($this->routeIs('dashboard.questions.index'))
		{
			return [
				'tags' => 'string'
			];
		}
		else if ($this->routeIs('dashboard.questions.store'))
		{
			return [
				'title' => 'required',
				'body' => 'required',
				'user_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN)),
					'integer'
				]
			];
		}
		else if ($this->routeIs('dashboard.questions.update'))
		{
			return [
				'user_id' => 'integer'
			];
		}
		else if ($this->routeIs('dashboard.questions.attach') || $this->routeIs('dashboard.questions.detach'))
		{
			return [
				'question_id' => 'required|integer',
				'tag_id' => 'required|integer'
			];
		}

		return [];
	}
}