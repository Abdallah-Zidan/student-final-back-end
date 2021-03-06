<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Tag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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
	 * @param \App\Tag $tag
	 *
	 * @return bool
	 */
	public function view(User $user, Tag $tag)
	{
		return $user->can('viewAny', Tag::class);
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
	 * @param \App\Tag $tag
	 *
	 * @return bool
	 */
	public function update(User $user, Tag $tag)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\Tag $tag
	 *
	 * @return bool
	 */
	public function delete(User $user, Tag $tag)
	{
		return $user->type === UserType::getTypeString(UserType::ADMIN);
	}
}