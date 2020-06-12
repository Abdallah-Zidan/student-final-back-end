<?php

namespace App\Policies;

use App\Department;
use App\Enums\UserType;
use App\Faculty;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
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
	 * @param \App\Department $department
	 *
	 * @return bool
	 */
	public function view(User $user, Department $department)
	{
		return $user->can('viewAny', Department::class);
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
	 * @param \App\Department $department
	 *
	 * @return bool
	 */
	public function update(User $user, Department $department)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\Department $department
	 *
	 * @return bool
	 */
	public function delete(User $user, Department $department)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can attach models.
	 *
	 * @param \App\User $user
	 * @param \App\Faculty $faculty
	 *
	 * @return bool
	 */
	public function attach(User $user, Faculty $faculty)
	{
		return ($user->type === UserType::getTypeString(UserType::MODERATOR) && $user->profileable->faculty->id === $faculty->id) ||
				$user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can detach models.
	 *
	 * @param \App\User $user
	 * @param \App\Faculty $faculty
	 *
	 * @return bool
	 */
	public function detach(User $user, Faculty $faculty)
	{
		return ($user->type === UserType::getTypeString(UserType::MODERATOR) && $user->profileable->faculty->id === $faculty->id) ||
				$user->type === UserType::getTypeString(UserType::ADMIN);
	}
}