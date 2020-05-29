<?php

namespace App\Policies;

use App\Enums\UserType;
use App\File;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\User $user
	 * @param mixed $resource The *Post* / *Event* object.
	 *
	 * @return bool
	 */
	public function viewAny(User $user, $resource)
	{
		return $user->can('viewAny', [get_class($resource), $resource->scopeable]);
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\User $user
	 * @param \App\File $file
	 * @param mixed $resource The *Post* / *Event* object.
	 *
	 * @return bool
	 */
	public function view(User $user, File $file, $resource)
	{
		return $user->can('viewAny', [File::class, $resource]);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\User $user
	 * @param mixed $resource The *Post* / *Event* object.
	 *
	 * @return bool
	 */
	public function create(User $user, $resource)
	{
		return $resource->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\User $user
	 * @param \App\File $file
	 * @param mixed $resource The *Post* / *Event* object.
	 *
	 * @return bool
	 */
	public function update(User $user, File $file, $resource)
	{
		return $resource->user->id === $user->id ||
			   $user->type === UserType::getTypeString(UserType::ADMIN);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\User $user
	 * @param \App\File $file
	 * @param mixed $resource The *Post* / *Event* object.
	 *
	 * @return bool
	 */
	public function delete(User $user, File $file, $resource)
	{
		return $user->can('delete', [$resource, $resource->scopeable]);
	}
}