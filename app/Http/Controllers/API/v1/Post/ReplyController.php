<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
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
    public function index(Request $request, Post $post, Comment $comment)
    {
        return $this->repo->getAllCommentReplies($comment);
    }

    /**
     * Create Reply
     *
     * @param StoreCommentRequest $request
     * @param Post $post
     * @param Comment $comment
     * @return response
     */
    public function store(StoreCommentRequest $request, Post $post, Comment $comment)
    {
        if ($request->user()->can('create', [$comment, $post]))
            return $this->repo->create(
                $request->user()->id,
                $comment,
                $request->body
            );
        return response([], 403);
    }

    /**
     * Update Reply
     *
     * @param StoreCommentRequest $request
     * @param Post $post
     * @param Comment $comment
     * @param Comment $reply
     * @return response
     */
    public function update(StoreCommentRequest $request, Post $post, Comment $comment, Comment $reply)
    {
        if ($request->user()->can('update', [$reply, $post, $comment]))
            return $this->repo->update($reply, $request->body);
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
    public function destroy(Request $request, Post $post, Comment $comment, Comment $reply)
    {
        if ($request->user()->can('delete', [$reply, $post, $comment]))
            return $this->repo->delete($reply);
        return response([], 403);
    }
}
