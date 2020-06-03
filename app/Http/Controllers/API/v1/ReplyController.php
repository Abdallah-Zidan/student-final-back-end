<?php

namespace App\Http\Controllers\API\v1;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Post;
use App\Repositories\ReplyRepository;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    private $repo;


    public function __construct(ReplyRepository $repo)
    {
        $this->repo = $repo;
    }


    /**
     * Get All Comment Replies 
     *
     * @param Request $request
     * @param Post $post
     * @param Comment $comment
     * @return Comment::Collection
     */
    public function index(Request $request, Comment $comment)
    {
        if ($request->user()->can('view', $comment->parent)) {
            return $this->repo->getAll($comment);
        }
        return response([], 403);
    }

    /**
     * Create Reply
     *
     * @param CommentRequest $request
     * @param Post $post
     * @param Comment $comment
     * @return response
     */
    public function store(CommentRequest $request, Comment $comment)
    {
        if ($request->user()->can('create', [Comment::class, $comment->parent])) {
            $reply = $this->repo->create(
                $comment,
                $request->only(['body'])
            );
            return response([
                'data' => [
                    'reply' => ['id' => $reply->id]
                ]
            ], 201);
        }
        return response([], 403);
    }

    /**
     * Update Reply
     *
     * @param CommentRequest $request
     * @param Post $post
     * @param Comment $comment
     * @param Comment $reply
     * @return response
     */
    public function update(CommentRequest $request, Comment $comment,  $reply)
    {
        $reply = $comment->replies()->findOrFail($reply);
        if ($request->user()->can('update', $reply)) {
            if ($this->repo->update($reply, $request->only(['body'])))
                return response([], 204);
        }
        return response([], 403);
    }

    /**
     * Delete Reply
     *
     * @param Request $request
     * @param Post $post
     * @param Comment $comment
     * @param Comment $reply
     * @return response
     */
    public function destroy(Request $request, Comment $comment,  $reply)
    {
        $reply = $comment->replies()->findOrFail($reply);
        if ($request->user()->can('delete', [$reply, $comment]))
            if ($this->repo->delete($reply))
                return response([], 204);
        return response([], 403);
    }
}
