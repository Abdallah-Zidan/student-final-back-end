<?php

namespace App\Http\Controllers\API\v1;

use App\Enums\EventScope;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Http\Resources\FileResource;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
	/**
	 * The event repository object.
	 *
	 * @var \App\Repositories\EventRepository
	 */
	private $repo;

	/**
	 * Create a new EventController object.
	 *
	 * @param \App\Repositories\EventRepository $repo The event repository object.
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
		$model = $request->group ? EventScope::getScopeModel($request->group) : null;
		$group = $model ? $model::findOrFail($request->group_id) : null;

		if ($user->can('viewAny', [Event::class, $group]))
		{
			$events = $this->repo->getAll($user, $group, $request->type);

			return new EventCollection($events);
		}

		return response([], 403);
	}

	/**
	 * Store an event.
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

		if ($user->can('create', [Event::class, $group, $request->type]))
		{
			$event = $this->repo->create($user, $group, $request->only(['title', 'body', 'type', 'start_date', 'end_date', 'files']));

			return response([
				'data' => [
					'event' => [
						'id' => $event->id,
						'files' => FileResource::collection($event->files)
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show an event.
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
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user',
				'files'
			]);

			if ($event->scope !== EventScope::getScopeString(EventScope::ALL))
			{
				$event->load([
					'scopeable'
				]);
			}

			return response([
				'data' => [
					'event' => new EventResource($event)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update an event.
	 *
	 * @param \App\Http\Requests\EventRequest $request The request object.
	 * @param \App\Event $event The event object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(EventRequest $request, Event $event)
	{
		if ($request->user()->can('update', $event))
		{
			$this->repo->update($event, $request->only(['title', 'body', 'start_date', 'end_date']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy an event.
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