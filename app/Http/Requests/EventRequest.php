<?php

namespace App\Http\Requests;

use App\Enums\EventScope;
use App\Enums\EventType;
use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
				'group' => [
					Rule::requiredIf($this->user()->type !== UserType::getTypeString(UserType::COMPANY)),
					'integer',
					'between:0,' . (count(EventScope::$scopes) - 1)
				],
				'group_id' => [
					Rule::requiredIf($this->user()->type !== UserType::getTypeString(UserType::COMPANY) && $this->has('group') && $this->group != 2),
					'integer'
				],
				'type' => 'required|integer|between:0,' . (count(EventType::$types) - 1)
			];
		}
		else if ($this->routeIs('events.store'))
		{
			return [
				'group' => 'required|integer|between:0,' . (count(EventScope::$scopes) - 1),
				'group_id' => 'required_unless:group,2|integer',
				'title' => 'required',
				'body' => 'required',
				'type' => 'required|integer|between:0,' . (count(EventType::$types) - 1),
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
		else if ($this->routeIs('dashboard.events.index'))
		{
			return [
				'type' => 'integer|between:0,' . (count(EventType::$types) - 1)
			];
		}
		else if ($this->routeIs('dashboard.events.store'))
		{
			return [
				'group' => 'required|integer|between:0,' . (count(EventScope::$scopes) - 1),
				'group_id' => 'required_unless:group,2|integer',
				'title' => 'required',
				'body' => 'required',
				'type' => 'required|integer|between:0,' . (count(EventType::$types) - 1),
				'start_date' => 'date',
				'end_date' => 'date|after_or_equal:start_date',
				'user_id' => [
					Rule::requiredIf($this->user()->type === UserType::getTypeString(UserType::ADMIN)),
					'integer'
				]
			];
		}
		else if ($this->routeIs('dashboard.events.update'))
		{
			return [
				'group' => 'integer|between:0,' . (count(EventScope::$scopes) - 1),
				'group_id' => 'integer',
				'type' => 'integer|between:0,' . (count(EventType::$types) - 1),
				'start_date' => 'date',
				'end_date' => 'date|after_or_equal:start_date',
				'user_id' => 'integer'
			];
		}

		return [];
	}
}