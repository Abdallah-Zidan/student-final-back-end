<?php

namespace App\Policies;

use App\Enums\UserType;
use App\File;
use App\Tool;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\User $user
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 *
	 * @return bool
	 */
	public function viewAny(User $user, $parent)
	{
		if ($parent instanceof Tool)
			return $user->can('viewAny', [Tool::class, $parent->faculty]);

		return $user->can('viewAny', [get_class($parent), $parent->scopeable]);
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\User $user
	 * @param \App\File $file
	 *
	 * @return bool
	 */
	public function view(User $user, File $file)
	{
		return $user->can('viewAny', [File::class, $file->resourceable]);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 *
	 * @return bool
	 */
	public function create(User $user, $parent)
	{
		return $parent->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\User $user
	 * @param \App\File $file
	 *
	 * @return bool
	 */
	public function update(User $user, File $file)
	{
		return $file->resourceable->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\File $file
	 *
	 * @return bool
	 */
	public function delete(User $user, File $file)
	{
		return $user->can('delete', $file->resourceable);
	}
}