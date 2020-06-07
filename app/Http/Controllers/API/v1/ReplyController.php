<?php

namespace App\Http\Controllers\API\v1;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\ReplyCollection;
use App\Http\Resources\ReplyResource;
use App\Post;
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
     * Get all replies of a comment.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param \App\Comment $comment The comment object.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Comment $comment)
    {
        if ($request->user()->can('viewAny', [Comment::class, $comment]))
        {
            $replies = $this->repo->getAll($comment);
            return new ReplyCollection($replies);
        }
        return response([], 403);
    }

    /**
     * Store a reply.
     *
     * @param \App\Http\Requests\CommentRequest $request The request object.
     * @param \App\Comment $comment The comment object.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, Comment $comment)
    {
        if ($request->user()->can('create', [Comment::class, $comment])) {
            $reply = $this->repo->create(
                $comment,
                $request->only(['body'])
            );
            return response([
                'data' => [
                    'reply' => [
                        'id' => $reply->id
                    ]
                ]
            ], 201);
        }
        return response([], 403);
    }
    /**
     * Show a reply.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param \App\Comment $comment The comment object.
     * @param int $reply The reply id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Comment $comment, int $reply)
    {
        $reply = $comment->replies()->findOrFail($reply);

        if ($request->user()->can('view', $reply))
        {
            $reply->load('user');

            return response([
                'data' => [
                    'reply' => new ReplyResource($reply)
                ]
            ]);
        }

        return response([], 403);
    }

    /**
     * Update a reply.
     *
     * @param \App\Http\Requests\CommentRequest $request The request object.
     * @param \App\Comment $comment The comment object.
     * @param int $reply The reply id.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, Comment $comment, int $reply)
    {
        $reply = $comment->replies()->findOrFail($reply);
        if ($request->user()->can('update', $reply)) 
        {
            $this->repo->update($reply, $request->only(['body']));
            return response([], 204);
        }
        return response([], 403);
    }

    /**
     * Destroy a reply.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param \App\Comment $comment The comment object.
     * @param int $reply The reply id.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comment $comment,  $reply)
    {
        $reply = $comment->replies()->findOrFail($reply);
        if ($request->user()->can('delete', $reply))
        { 
            if ($this->repo->delete($reply))
            return response([], 204);
        }
        return response([], 403);
    }
}
