<?php

namespace App\Repositories\Dashboard;

use App\DepartmentFaculty;
use App\DepartmentFacultyUser;
use App\Enums\EventScope;
use App\Enums\UserType;
use App\Event;
use App\Faculty;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class EventRepository
{
	/**
	 * Get all events.
	 *
	 * @param \App\User $user The user object.
	 * @param int $type The event type.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(User $user, int $type = null, int $items = 10)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
			return $this->getAllForMoedrator($user->profileable->faculty, $type, $items);
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return $this->getAllForAdmin($type, $items);

		return new LengthAwarePaginator([], 0, 10, 1, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page'
		]);
	}

	/**
	 * Create a event.
	 *
	 * @param array $data The event data.
	 *
	 * @return \App\Event
	 */
	public function create(array $data)
	{
		return Event::create($data);
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
	 * Get all events related to the moedrator groups.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param int $type The event type.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForMoedrator(Faculty $faculty, int $type = null, int $items)
	{
		$department_faculties = DepartmentFaculty::where('faculty_id', $faculty->id)->get();
		$department_faculty_users = DepartmentFacultyUser::whereIn('department_faculty_id', $department_faculties->pluck('id'))->get();

		$events = Event::with('user')->where(function ($query) use ($faculty, $department_faculty_users) {
			$query->where([
				['scopeable_type', EventScope::getScopeModel(EventScope::FACULTY)],
				['scopeable_id', $faculty->id]
			])->orWhere([
				['scopeable_type', EventScope::getScopeModel(EventScope::UNIVERSITY)],
				['scopeable_id', $faculty->university->id]
			])->orWhere(function ($query) use ($department_faculty_users) {
				$query->where('scopeable_type', EventScope::getScopeString(EventScope::ALL))
					  ->whereIn('user_id', $department_faculty_users->pluck('user_id'));
			});
		});

		if (!is_null($type))
			$events->where('type', $type);

		$events = $events->paginate($items);

		$events->whereIn('scopeable_type', [
			EventScope::getScopeModel(EventScope::FACULTY),
			EventScope::getScopeModel(EventScope::UNIVERSITY)
		])->load('scopeable');

		return $events;
	}

	/**
	 * Get all events.
	 *
	 * @param int $type The event type.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForAdmin(int $type = null, int $items)
	{
		$events = Event::with('user');
		
		if (!is_null($type))
			$events->where('type', $type);

		$events = $events->paginate($items);

		$events->whereIn('scopeable_type', [
			EventScope::getScopeModel(EventScope::FACULTY),
			EventScope::getScopeModel(EventScope::UNIVERSITY)
		])->load('scopeable');

		return $events;
	}
}