<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	/**
	 * The comment repository object.
	 *
	 * @var \App\Repositories\CommentRepository
	 */
	private $repo;

	/**
	 * Create a new CommentController object.
	 *
	 * @param \App\Repositories\CommentRepository $repo The comment repository object.
	 */
	public function __construct(CommentRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all comments.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $event)
	{
		if ($group)
			$event = $group->events()->findOrFail($event);
		else
			$event = Event::findOrFail($event);

		$comments = $this->repo->getAllComments($event);
		return new CommentCollection($comments);
	}

	/**
	 * Store a comment.
	 *
	 * @param \App\Http\Requests\StoreCommentRequest $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreCommentRequest $request, $group, int $event)
	{
		if ($group)
			$event = $group->events()->findOrFail($event);
		else
			$event = Event::findOrFail($event);

		return $this->repo->create(
			$request->user()->id,
			$event,
			$request->body
		);
	}

	/**
	 * Show a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $event, int $comment)
	{
		if ($group)
			$comment = $group->events()->findOrFail($event)->comments()->findOrFail($comment);
		else
			$comment = Event::findOrFail($event)->comments()->findOrFail($comment);

		return response([
			'data' => [
				'comment' => new CommentResource($comment)
			]
		]);
	}

	/**
	 * Update a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event, int $comment)
	{
		if ($group)
			$comment = $group->events()->findOrFail($event)->comments()->findOrFail($comment);
		else
			$comment = Event::findOrFail($event)->comments()->findOrFail($comment);

		if ($request->user()->can('update', [$comment, $event]))
		{
			return $this->repo->update($comment, $request->body);
		}

		return response('', 403);
	}

	/**
	 * Destroy a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event, int $comment)
	{
		if ($group)
			$comment = $group->events()->findOrFail($event)->comments()->findOrFail($comment);
		else
			$comment = Event::findOrFail($event)->comments()->findOrFail($comment);

		if ($request->user()->can('delete', [$comment, $event]))
		{
			return $this->repo->delete($comment);
		}

		return response('', 403);
	}
}