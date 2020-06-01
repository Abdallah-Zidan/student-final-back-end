<?php

namespace App\Http\Controllers\API\v1\Tool;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\ReplyRepository;
use App\Tool;
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
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, Tool $tool, int $comment)
	{
		$comment = $tool->comments()->findOrFail($comment);

		return $this->repo->getAllCommentReplies($comment);
	}

	/**
	 * Store a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Tool $tool, int $comment)
	{
		$comment = $tool->comments()->findOrFail($comment);

		if ($request->user()->can('create', [$comment, $tool]))
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
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Tool $tool, int $comment, int $reply)
	{
		$reply = $tool->comments()->findOrFail($comment)->replies()->findOrFail($reply);

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
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return void
	 */
	public function update(Request $request, Tool $tool, int $comment, int $reply)
	{
		$reply = $tool->comments()->findOrFail($comment)->replies()->findOrFail($reply);

		$comment = Comment::find($comment);

		if ($request->user()->can('update', [$reply, $tool, $comment]))
			return $this->repo->update($reply, $request->body);

		return response([], 403);
	}

	/**
	 * Destroy a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, Tool $tool, int $comment, int $reply)
	{
		$reply = $tool->comments()->findOrFail($comment)->replies()->findOrFail($reply);

		$comment = Comment::find($comment);

		if ($request->user()->can('delete', [$reply, $tool, $comment]))
			return $this->repo->delete($reply);

		return response([], 403);
	}
}