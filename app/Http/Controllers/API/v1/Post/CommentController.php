<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostComment;
use App\Http\Resources\CommentResource;
use App\Post;
use App\Repositories\PostCommentRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private PostCommentRepository $repo;

    public function __construct(PostCommentRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Show all Post Comments
     *
     * @param Request $request
     * @return Comment::Collection
     */
    public function index(Request $request)
    {
       return $this->repo->getAllPostComments($request->post);
    }
    /**
     * Store new Comment
     * @param StorePostComment $request
     * @return response
     */
    public function store(StorePostComment $request)
    {
        return $this->repo->create(
            $request->user()->id,
            $request->post,
            $request->body
        );
    }

    /**
     * update Comment
     *
     * @param StorePostComment $request
     * @return response
     */
    public function update(StorePostComment $request)
    {
        return $this->repo->update($request->comment, $request->body);
    }
    /**
     * Delete Comment
     *
     * @param Request $request
     * @return response
     */
    public function destroy(Request $request)
    {
        return $this->repo->delete($request->comment);
    }
}
