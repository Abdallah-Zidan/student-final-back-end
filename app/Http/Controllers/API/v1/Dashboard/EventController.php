<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Enums\EventScope;
use App\Enums\UserType;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Repositories\Dashboard\EventRepository;
use App\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
	/**
	 * The event repository object.
	 *
	 * @var \App\Repositories\Dashboard\EventRepository
	 */
	private $repo;

	/**
	 * Create a new EventController object.
	 *
	 * @param \App\Repositories\Dashboard\EventRepository $repo The event repository object.
	 */
	public function __construct(EventRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all events.
	 *
	 * @param \App\Http\Requests\EventRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(EventRequest $request)
	{
		$user = $request->user();

		if ($user->can('viewAny', [Event::class, null]))
		{
			$items = intval($request->items) ?: 10;
			$events = $this->repo->getAll($user, $request->type, $items);

			return new EventCollection($events);
		}

		return response([], 403);
	}

	/**
	 * Store a event.
	 *
	 * @param \App\Http\Requests\EventRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(EventRequest $request)
	{
		$user = $request->user();
		$model = EventScope::getScopeModel($request->group);
		$group = $model ? $model::findOrFail($request->group_id) : null;

		if ($user->type === UserType::getTypeString(UserType::ADMIN))
			User::findOrFail($request->user_id);

		if ($user->can('create', [Event::class, $group, $request->type]))
		{
			$data = $request->only(['title', 'body', 'type', 'start_date', 'end_date']) + [
				'scopeable_type' => get_class($group),
				'scopeable_id' => $group->id
			] + ($user->type === UserType::getTypeString(UserType::ADMIN) ? [
				'user_id' => $request->user_id
			] : [
				'user_id' => $user->id
			]);
			$event = $this->repo->create($data);

			return response([
				'data' => [
					'event' => [
						'id' => $event->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Event $event The event object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Event $event)
	{
		if ($request->user()->can('view', $event))
		{
			$event->load([
				'user',
				'interests'
			]);

			if ($event->scope === EventScope::getScopeString(EventScope::FACULTY) || $event->scope === EventScope::getScopeString(EventScope::UNIVERSITY))
				$event->load('scopeable');

			return response([
				'data' => [
					'event' => new EventResource($event)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a event.
	 *
	 * @param \App\Http\Requests\EventRequest $request The request object.
	 * @param \App\Event $event The event object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EventRequest $request, Event $event)
	{
		$user = $request->user();
		$model = EventScope::getScopeModel($request->group);
		$group = $model::findOrFail($request->group_id);

		if ($user->type === UserType::getTypeString(UserType::ADMIN))
			User::findOrFail($request->user_id);

		if ($user->can('update', $event))
		{
			$data = $request->only(['title', 'body', 'start_date', 'end_date']) + [
				'scopeable_type' => get_class($group),
				'scopeable_id' => $group->id
			] + ($user->type === UserType::getTypeString(UserType::ADMIN) ? $request->only(['type', 'user_id']) : []);
			$this->repo->update($event, $data);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Event $event The event object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Event $event)
	{
		if ($request->user()->can('delete', $event))
		{
			$this->repo->delete($event);

			return response([], 204);
		}

		return response([], 403);
	}
}