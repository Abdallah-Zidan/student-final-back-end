<?php

namespace App\Policies;

use App\DepartmentFaculty;
use App\Enums\UserType;
use App\Faculty;
use App\Post;
use App\University;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\User $user
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return bool
	 */
	public function viewAny(User $user, $group)
	{
		if ($user->type === UserType::getTypeString(UserType::STUDENT) ||
			$user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
		{
			if ($group instanceof DepartmentFaculty)
			{
				if ($user->departmentFaculties()->find($group->id))
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
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($group instanceof DepartmentFaculty)
			{
				if (DepartmentFaculty::where('faculty_id', $user->profileable->faculty->id)->find($group->id))
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
	 * @param \App\Post $post
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return bool
	 */
	public function view(User $user, Post $post, $group)
	{
		return $user->can('viewAny', [Post::class, $group]);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return bool
	 */
	public function create(User $user, $group)
	{
		if ($user->type === UserType::getTypeString(UserType::STUDENT) ||
			$user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
		{
			if ($group instanceof DepartmentFaculty)
			{
				if ($user->departmentFaculties()->find($group->id))
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
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\User $user
	 * @param \App\Post $post
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return bool
	 */
	public function update(User $user, Post $post, $group)
	{
		return $post->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\Post $post
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return bool
	 */
	public function delete(User $user, Post $post, $group)
	{
		if ($post->user->id === $user->id ||
			$user->type === UserType::getTypeString(UserType::ADMIN))
			return true;
		else if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($group instanceof DepartmentFaculty)
			{
				if (DepartmentFaculty::where('faculty_id', $user->profileable->faculty->id)->find($group->id))
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