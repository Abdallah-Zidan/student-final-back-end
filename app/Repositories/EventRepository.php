<?php

namespace App\Repositories;

use App\Enums\EventScope;
use App\Event;
use App\Faculty;
use App\University;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class EventRepository
{
	/**
	 * Get all events related to the *Faculty* / *University* / *All* group.
	 *
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $type The event type.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($group, int $type)
	{
		if (is_null($group))
			return $this->getAllInAll($type);
		else if ($group instanceof Faculty)
			return $this->getAllInFaculty($group, $type);
		else if ($group instanceof University)
			return $this->getAllInUniversity($group, $type);

		return new LengthAwarePaginator([], 0, 10, 1, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page'
		]);
	}

	/**
	 * Create an event related to the given group and user.
	 *
	 * @param \App\User $user The user object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param array $data The event data.
	 *
	 * @return \App\Event
	 */
	public function create(User $user, $group, array $data)
	{
		$event = $user->events()->create($data + [
			'scopeable_type' => is_null($group) ? EventScope::getScopeString(EventScope::ALL) : get_class($group),
			'scopeable_id' => is_null($group) ? 0 : $group->id
		]);

		if (array_key_exists('files', $data))
		{
			foreach ($data['files'] as $file)
			{
				$path = Storage::disk('local')->put('files/events/' . $event->id, $file);
				$event->files()->create([
					'name' => $file->getClientOriginalName(),
					'path' => $path,
					'mime' => Storage::mimeType($path)
				]);
			}
		}

		return $event;
	}

	/**
	 * Update an existing event.
	 *
	 * @param \App\Event $event The event object.
	 * @param array $data The event data.
	 *
	 * @return void
	 */
	public function update(Event $event, array $data)
	{
		$event->update($data);
	}

	/**
	 * Delete an existing event.
	 *
	 * @param \App\Event $event The event object.
	 *
	 * @return void
	 */
	public function delete(Event $event)
	{
		$event->delete();
	}

	/**
	 * Get all events related to the *All* group.
	 * 
	 * @param int $type The event type.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInAll(int $type)
	{
		$events = Event::with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files'
		])->where([
			['scopeable_type', EventScope::getScopeString(EventScope::ALL)],
			['type', $type]
		])->orderBy('created_at', 'desc')->paginate(10);

		return $events;
	}

	/**
	 * Get all events related to the *Faculty* group.
	 *
	 * @param \App\Faculty $faculty The *Faculty* object.
	 * @param int $type The event type.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInFaculty(Faculty $faculty, int $type)
	{
		$events = Event::with([
			'user',
			'scopeable',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files'
		])->where([
			['scopeable_type', EventScope::getScopeModel(EventScope::FACULTY)],
			['scopeable_id', $faculty->id],
			['type', $type]
		])->orderBy('created_at', 'desc')->paginate(10);

		return $events;
	}

	/**
	 * Get all events related to the *University* group.
	 *
	 * @param University $university The *University* object.
	 * @param int $type The event type.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInUniversity(University $university, $type)
	{
		$events = Event::with([
			'user',
			'scopeable',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files'
		])->where([
			['scopeable_type', EventScope::getScopeModel(EventScope::UNIVERSITY)],
			['scopeable_id', $university->id],
			['type', $type]
		])->orderBy('created_at', 'desc')->paginate(10);

		return $events;
	}
}