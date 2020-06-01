<?php

namespace App\Http\Controllers\API\v1\Tool;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use App\Tool;
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
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, Tool $tool)
	{
		$comments = $this->repo->getAllComments($tool);
		return new CommentCollection($comments);
	}

	/**
	 * Store a comment.
	 *
	 * @param \App\Http\Requests\StoreCommentRequest $request The request object.
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreCommentRequest $request, Tool $tool)
	{
		return $this->repo->create(
			$request->user()->id,
			$tool,
			$request->body
		);
	}

	/**
	 * Show a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Tool $tool, int $comment)
	{
		$comment = $tool->comments()->findOrFail($comment);

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
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 *
	 * @return void
	 */
	public function update(Request $request, Tool $tool, int $comment)
	{
		$comment = $tool->comments()->findOrFail($comment);

		if ($request->user()->can('update', [$comment, $tool]))
		{
			return $this->repo->update($comment, $request->body);
		}

		return response('', 403);
	}

	/**
	 * Destroy a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $comment The comment id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, Tool $tool, int $comment)
	{
		$comment = $tool->comments()->findOrFail($comment);

		if ($request->user()->can('delete', [$comment, $tool]))
		{
			return $this->repo->delete($comment);
		}

		return response('', 403);
	}
}