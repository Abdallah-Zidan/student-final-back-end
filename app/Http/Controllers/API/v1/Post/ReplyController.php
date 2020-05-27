<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostComment;
use App\Repositories\PostReplyRepository;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    private PostReplyRepository $repo;

    /**
     * Show all Comment replies
     *
     * @param Request $request
     * @return Comment::Collection
     */
    public function __construct(PostReplyRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Store new Reply
     * @param StorePostComment $request
     * @return response
     */
    public function index(Request $request)
    {
        return $this->repo->getAllCommentReplies($request->comment);
    }

    public function store(StorePostComment $request)
    {
        return $this->repo->create(
            $request->user()->id,
            $request->comment,
            $request->body
        );
    }

    /**
     * update Reply
     *
     * @param StorePostComment $request
     * @return response
     */
    public function update(StorePostComment $request)
    {
        return $this->repo->update($request->reply, $request->body);
    }

    /**
     * Delete Reply
     *
     * @param Request $request
     * @return response
     */
    public function destroy(Request $request)
    {
        return $this->repo->delete($request->reply);
    }
}
