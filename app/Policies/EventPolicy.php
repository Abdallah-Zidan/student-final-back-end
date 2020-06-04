<?php

namespace App\Policies;

use App\Enums\EventScope;
use App\Enums\EventType;
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
				return true;
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
			return true;
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if (is_null($group))
				return true;
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
	 *
	 * @return bool
	 */
	public function view(User $user, Event $event)
	{
		if ($event->scope === EventScope::getScopeString(EventScope::ALL))
			return $user->can('viewAny', [Event::class, null]);

		return $user->can('viewAny', [Event::class, $event->scopeable]);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $type The event type.
	 *
	 * @return bool
	 */
	public function create(User $user, $group, int $type)
	{
		if ($user->type === UserType::getTypeString(UserType::STUDENT) ||
			$user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
		{
			if ($type === EventType::NORMAL ||
			   ($user->type === UserType::getTypeString(UserType::TEACHING_STAFF) && $type === EventType::ANNOUNCEMENT))
			{
				if (is_null($group))
					return true;
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
		}
		else if ($user->type === UserType::getTypeString(UserType::COMPANY))
		{
			if ($type !== EventType::ANNOUNCEMENT)
				return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($type === EventType::NORMAL ||
				$type === EventType::ANNOUNCEMENT)
			{
				if (is_null($group))
					return true;
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
	 *
	 * @return bool
	 */
	public function update(User $user, Event $event)
	{
		return $event->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\Event $event
	 *
	 * @return bool
	 */
	public function delete(User $user, Event $event)
	{
		if ($event->user->id === $user->id ||
			$user->type === UserType::getTypeString(UserType::ADMIN))
			return true;
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			$group = is_null($event->scope) ? null : $event->scopeable;

			if (is_null($group))
			{
				if ($event->user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($user) {
					return $department_faculty->faculty->id == $user->profileable->faculty->id;
				}))
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

		return false;
	}
}