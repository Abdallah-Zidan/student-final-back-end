<?php

namespace App\Http\Controllers\API\v1;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Post;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $repo;

    public function __construct(CommentRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Get All Post Comments 
     *
     * @param Request $request
     * @param Post $post
     * @return Comment::Collection
     */
    public function index(Request $request, $parent)
    {
        if ($request->user()->can('viewAny', [Comment::class, $parent])) {
            return $this->repo->getAll($parent);
        }
        return response([], 403);
    }
    /**
     * Create Comment
     *
     * @param CommentRequest $request
     * @param Post $post
     * @return response
     */
    public function store(CommentRequest $request, $parent)
    {
        if ($request->user()->can('create', [Comment::class, $parent])) {
            $comment = $this->repo->create(
                $parent,
                $request->only(['body'])
            );
            return response([
                'data' => [
                    'comment' => ['id' => $comment->id]
                ]
            ], 201);
        }
        return response([], 403);
    }

    /**
     * Update Comment
     *
     * @param CommentRequest $request
     * @param Post $post
     * @param Comment $comment
     * @return response
     */
    public function update(CommentRequest $request, $parent,  $comment)
    {
        $comment = $parent->comments()->findOrFail($comment);
        if ($request->user()->can('update', $comment)) {
            if ($this->repo->update($comment,  $request->only(['body'])))
                return response([], 204);
        }
        return response([], 403);
    }
    /**
     * Delete Comment
     *
     * @param Request $request
     * @param Post $post
     * @param Comment $comment
     * @return response
     */
    public function destroy(Request $request, $parent,  $comment)
    {
        $comment = $parent->comments()->findOrFail($comment);
        if ($request->user()->can('delete', [$comment, $parent]))
            if ($this->repo->delete($comment))
                return response([], 204);
        return response([], 403);
    }
}
