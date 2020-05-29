<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
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
    public function index(Request $request, Post $post)
    {
        return $this->repo->getAllComments($post);
    }
    /**
     * Create Comment
     *
     * @param StoreCommentRequest $request
     * @param Post $post
     * @return response
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        return $this->repo->create(
            $request->user()->id,
            $post,
            $request->body
        );
    }

    /**
     * Update Comment
     *
     * @param StoreCommentRequest $request
     * @param Post $post
     * @param Comment $comment
     * @return response
     */
    public function update(StoreCommentRequest $request, Post $post, Comment $comment)
    {
        if ($request->user()->can('update', [$comment, $post]))
            return $this->repo->update($comment, $request->body);
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
    public function destroy(Request $request, Post $post, Comment $comment)
    {
        if ($request->user()->can('delete', [$comment, $post]))
            return $this->repo->delete($comment);
        return response([], 403);
    }
}
