<?php

namespace App\Repositories;

use App\Event;
use App\User;

class InterestRepository
{
	/**
	 * Create an interest related to the given user and event.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\Event $event The event object.
	 *
	 * @return void
	 */
	public function create(User $user, Event $event)
	{
		$event->interests()->attach($user);
	}

	/**
	 * Delete an existing interest.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\Event $event The event object.
	 *
	 * @return void
	 */
	public function delete(User $user, Event $event)
	{
		$event->interests()->detach($user);
	}
}