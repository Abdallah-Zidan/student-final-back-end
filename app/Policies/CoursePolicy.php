<?php

namespace App\Policies;

use App\Course;
use App\DepartmentFaculty;
use App\Enums\UserType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\User $user
	 *
	 * @return bool
	 */
	public function viewAny(User $user)
	{
		return $user->type === UserType::getTypeString(UserType::MODERATOR) ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\User $user
	 * @param \App\Course $course
	 *
	 * @return bool
	 */
	public function view(User $user, Course $course)
	{
		return $user->can('viewAny', Course::class);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 *
	 * @return bool
	 */
	public function create(User $user)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\User $user
	 * @param \App\Course $course
	 *
	 * @return bool
	 */
	public function update(User $user, Course $course)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\Course $course
	 *
	 * @return bool
	 */
	public function delete(User $user, Course $course)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can attach models.
	 *
	 * @param \App\User $user
	 * @param \App\DepartmentFaculty $department_faculty
	 *
	 * @return bool
	 */
	public function attach(User $user, DepartmentFaculty $department_faculty)
	{
		return ($user->type === UserType::getTypeString(UserType::MODERATOR) && $user->profileable->faculty->id === $department_faculty->faculty->id) ||
				$user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can detach models.
	 *
	 * @param \App\User $user
	 * @param \App\DepartmentFaculty $department_faculty
	 *
	 * @return bool
	 */
	public function detach(User $user, DepartmentFaculty $department_faculty)
	{
		return ($user->type === UserType::getTypeString(UserType::MODERATOR) && $user->profileable->faculty->id === $department_faculty->faculty->id) ||
				$user->type === UserType::getTypeString(UserType::ADMIN);
	}
}