<?php

namespace App\Http\Controllers\API\v1\Event;

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
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group)
	{
		if ($request->user()->can('viewAny', [Event::class, $group]))
		{
			$events = $this->repo->getAll($group);

			return new EventCollection($events);
		}

		return response('', 403);
	}

	/**
	 * Store an event.
	 *
	 * @param \App\Http\Requests\EventRequest $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(EventRequest $request, $group)
	{
		$user = $request->user();

		if ($user->can('create', [Event::class, $group]))
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

		return response('', 403);
	}

	/**
	 * Show an event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return void
	 */
	public function show(Request $request, $group, int $event)
	{
		$event = $group->events()->findOrFail($event);

		if ($request->user()->can('view', [$event, $group]))
		{
			$event->load([
				'user',
				'user.profileable',
				'scopeable',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user',
				'files'
			]);

			return response([
				'data' => [
					'event' => new EventResource($event)
				]
			]);
		}

		return response('', 403);
	}

	/**
	 * Update an event.
	 *
	 * @param \App\Http\Requests\EventRequest $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return void
	 */
	public function update(EventRequest $request, $group, int $event)
	{
		$event = $group->events()->findOrFail($event);

		if ($request->user()->can('update', [$event, $group]))
		{
			$this->repo->update($event, $request->only(['title', 'body', 'start_date', 'end_date']));

			return response('', 204);
		}

		return response('', 403);
	}

	/**
	 * Destroy an event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event)
	{
		$event = $group->events()->findOrFail($event);

		if ($request->user()->can('delete', [$event, $group]))
		{
			$this->repo->delete($event);

			return response('', 204);
		}

		return response('', 403);
	}
}