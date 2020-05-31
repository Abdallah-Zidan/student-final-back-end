<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Event;
use App\Faculty;
use App\University;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\User $user
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return bool
	 */
	public function viewAny(User $user, $group)
	{
		if ($user->type === UserType::getTypeString(UserType::STUDENT) ||
			$user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
		{
			if (is_null($group))
			{
				return true;
			}
			else if ($group instanceof Faculty)
			{
				if ($user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($group) {
					return $department_faculty->faculty->id == $group->id;
				}))
					return true;
			}
			else if ($group instanceof University)
			{
				if ($user->departmentFaculties->load('faculty.university')->first(function ($department_faculty) use ($group) {
					return $department_faculty->faculty->university->id == $group->id;
				}))
					return true;
			}
		}
		else if ($user->type === UserType::getTypeString(UserType::COMPANY))
		{
			return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if (is_null($group))
			{
				return true;
			}
			else if ($group instanceof Faculty)
			{
				if ($user->profileable->faculty->id === $group->id)
					return true;
			}
			else if ($group instanceof University)
			{
				if ($user->profileable->faculty->university->id === $group->id)
					return true;
			}
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\User $user
	 * @param \App\Event $event
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return bool
	 */
	public function view(User $user, Event $event, $group)
	{
		return $user->can('viewAny', [Event::class, $group]);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return bool
	 */
	public function create(User $user, $group)
	{
		if ($user->type === UserType::getTypeString(UserType::STUDENT) ||
			$user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
		{
			if (is_null($group))
			{
				return true;
			}
			else if ($group instanceof Faculty)
			{
				if ($user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($group) {
					return $department_faculty->faculty->id == $group->id;
				}))
					return true;
			}
			else if ($group instanceof University)
			{
				if ($user->departmentFaculties->load('faculty.university')->first(function ($department_faculty) use ($group) {
					return $department_faculty->faculty->university->id == $group->id;
				}))
					return true;
			}
		}
		else if ($user->type === UserType::getTypeString(UserType::COMPANY))
		{
			return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if (is_null($group))
			{
				return true;
			}
			else if ($group instanceof Faculty)
			{
				if ($user->profileable->faculty->id === $group->id)
					return true;
			}
			else if ($group instanceof University)
			{
				if ($user->profileable->faculty->university->id === $group->id)
					return true;
			}
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\User $user
	 * @param \App\Event $event
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return bool
	 */
	public function update(User $user, Event $event, $group)
	{
		return $event->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\Event $event
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return bool
	 */
	public function delete(User $user, Event $event, $group)
	{
		if ($event->user->id === $user->id ||
			$user->type === UserType::getTypeString(UserType::ADMIN))
			return true;
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($group instanceof Faculty)
			{
				if ($user->profileable->faculty->id === $group->id)
					return true;
			}
			else if ($group instanceof University)
			{
				if ($user->profileable->faculty->university->id === $group->id)
					return true;
			}
		}

		return false;
	}
}