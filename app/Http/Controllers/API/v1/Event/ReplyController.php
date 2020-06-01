<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Comment;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\ReplyRepository;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	/**
	 * The reply repository object.
	 *
	 * @var \App\Repositories\ReplyRepository
	 */
	private $repo;

	/**
	 * Create a new ReplyController object.
	 *
	 * @param \App\Repositories\ReplyRepository $repo The reply repository object.
	 */
	public function __construct(ReplyRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all replies.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $event, int $comment)
	{
		if ($group)
			$comment = $group->events()->findOrFail($event)->comments()->findOrFail($comment);
		else
			$comment = Event::findOrFail($event)->comments()->findOrFail($comment);

		return $this->repo->getAllCommentReplies($comment);
	}

	/**
	 * Store a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $group, int $event, int $comment)
	{
		if ($group)
			$comment = $group->events()->findOrFail($event)->comments()->findOrFail($comment);
		else
			$comment = Event::findOrFail($event)->comments()->findOrFail($comment);

		$event = Event::find($event);

		if ($request->user()->can('create', [$comment, $event]))
			return $this->repo->create(
				$request->user()->id,
				$comment,
				$request->body
			);
		return response([], 403);
	}

	/**
	 * Show a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $event, int $comment, int $reply)
	{
		if ($group)
			$reply = $group->events()->findOrFail($event)->comments()->findOrFail($comment)->replies()->findOrFail($reply);
		else
			$reply = Event::findOrFail($event)->comments()->findOrFail($comment)->replies()->findOrFail($reply);

		return response([
			'data' => [
				'comment' => new CommentResource($comment)
			]
		]);
	}

	/**
	 * Update a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event, int $comment, int $reply)
	{
		if ($group)
			$reply = $group->events()->findOrFail($event)->comments()->findOrFail($comment)->replies()->findOrFail($reply);
		else
			$reply = Event::findOrFail($event)->comments()->findOrFail($comment)->replies()->findOrFail($reply);

		$event = Event::find($event);
		$comment = Comment::find($comment);

		if ($request->user()->can('update', [$reply, $event, $comment]))
			return $this->repo->update($reply, $request->body);

		return response([], 403);
	}

	/**
	 * Destroy a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event, int $comment, int $reply)
	{
		if ($group)
			$reply = $group->events()->findOrFail($event)->comments()->findOrFail($comment)->replies()->findOrFail($reply);
		else
			$reply = Event::findOrFail($event)->comments()->findOrFail($comment)->replies()->findOrFail($reply);

		$event = Event::find($event);
		$comment = Comment::find($comment);

		if ($request->user()->can('delete', [$reply, $event, $comment]))
			return $this->repo->delete($reply);

		return response([], 403);
	}
}