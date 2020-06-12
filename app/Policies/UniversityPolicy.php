<?php

namespace App\Policies;

use App\Enums\UserType;
use App\University;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UniversityPolicy
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
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\User $user
	 * @param \App\University $university
	 *
	 * @return bool
	 */
	public function view(User $user, University $university)
	{
		return $user->can('viewAny', University::class);
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
	 * @param \App\University $university
	 *
	 * @return bool
	 */
	public function update(User $user, University $university)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\University $university
	 *
	 * @return bool
	 */
	public function delete(User $user, University $university)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}
}