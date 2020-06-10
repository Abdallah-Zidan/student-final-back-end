<?php

namespace App\Http\Controllers\API\v1;

use App\Event;
use App\Http\Controllers\Controller;
use App\Repositories\InterestRepository;
use Illuminate\Http\Request;

class InterestController extends Controller
{
	/**
	 * The interest repository object.
	 *
	 * @var \App\Repositories\InterestRepository
	 */
	private $repo;

	/**
	 * Create a new InterestController object.
	 *
	 * @param \App\Repositories\InterestRepository $repo The interest repository object.
	 */
	public function __construct(InterestRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Store an interest.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Event $event The event object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Event $event)
	{
		$user = $request->user();

		if ($user->can('view', $event))
		{
			$this->repo->create($user, $event);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Destroy an interest.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Event $event The event object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Event $event)
	{
		$user = $request->user();

		if ($user->can('view', $event))
		{
			$this->repo->delete($user, $event);

			return response([], 204);
		}

		return response([], 403);
	}
}