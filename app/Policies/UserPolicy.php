<?php

namespace App\Policies;

use App\CourseDepartmentFaculty;
use App\DepartmentFaculty;
use App\Enums\UserType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
	 * @param \App\User $new_user
	 *
	 * @return bool
	 */
	public function view(User $user, User $new_user)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($new_user->type === UserType::getTypeString(UserType::STUDENT) ||
				$new_user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
			{
				if ($new_user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($user) {
					return $department_faculty->faculty->id == $user->profileable->faculty->id;
				}))
					return true;
			}
			else if ($new_user->type === UserType::getTypeString(UserType::MODERATOR))
			{
				if ($new_user->profileable->faculty->id === $user->profileable->faculty->id)
					return true;
			}
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 * @param int $type The user type.
	 *
	 * @return bool
	 */
	public function create(User $user, int $type)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($type === UserType::STUDENT ||
				$type === UserType::TEACHING_STAFF ||
				$type === UserType::MODERATOR)
				return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\User $user
	 * @param \App\User $new_user
	 *
	 * @return bool
	 */
	public function update(User $user, User $new_user)
	{
		return $user->can('view', [User::class, $new_user]);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\User $new_user
	 *
	 * @return bool
	 */
	public function delete(User $user, User $new_user)
	{
		return $user->can('view', [User::class, $new_user]);
	}

	/**
	 * Determine whether the user can attachDepartment models.
	 *
	 * @param \App\User $user
	 * @param \App\User $new_user
	 * @param \App\DepartmentFaculty $department_faculty
	 *
	 * @return bool
	 */
	public function attachDepartment(User $user, User $new_user, DepartmentFaculty $department_faculty)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($user->profileable->faculty->id === $department_faculty->faculty->id &&
				$new_user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($user) {
					return $department_faculty->faculty->id == $user->profileable->faculty->id;
				}))
				return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can detach models.
	 *
	 * @param \App\User $user
	 * @param \App\User $new_user
	 * @param \App\DepartmentFaculty $department_faculty
	 *
	 * @return bool
	 */
	public function detachDepartment(User $user, User $new_user, DepartmentFaculty $department_faculty)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($user->profileable->faculty->id === $department_faculty->faculty->id &&
				$new_user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($user) {
					return $department_faculty->faculty->id == $user->profileable->faculty->id;
				}))
				return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can attachDepartment models.
	 *
	 * @param \App\User $user
	 * @param \App\User $new_user
	 * @param \App\CourseDepartmentFaculty $course_department_faculty
	 *
	 * @return bool
	 */
	public function attachCourse(User $user, User $new_user, CourseDepartmentFaculty $course_department_faculty)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($user->profileable->faculty->id === $course_department_faculty->departmentFaculty->faculty->id &&
				$new_user->departmentFaculties()->find($course_department_faculty->departmentFaculty->id) &&
				$new_user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($user) {
					return $department_faculty->faculty->id == $user->profileable->faculty->id;
				}))
				return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}

	/**
	 * Determine whether the user can detach models.
	 *
	 * @param \App\User $user
	 * @param \App\User $new_user
	 * @param \App\CourseDepartmentFaculty $course_department_faculty
	 *
	 * @return bool
	 */
	public function detachCourse(User $user, User $new_user, CourseDepartmentFaculty $course_department_faculty)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
		{
			if ($user->profileable->faculty->id === $course_department_faculty->departmentFaculty->faculty->id &&
				$new_user->departmentFaculties()->find($course_department_faculty->departmentFaculty->id) &&
				$new_user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($user) {
					return $department_faculty->faculty->id == $user->profileable->faculty->id;
				}))
				return true;
		}
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return true;

		return false;
	}
}