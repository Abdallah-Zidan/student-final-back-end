<?php

namespace App\Http\Controllers\API\v1;

use App\Comment;
use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Post;
use App\Question;
use App\Repositories\CommentRepository;
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
     * Get all comments of a parent.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $parent)
    {
        if ($request->user()->can('viewAny', [Comment::class, $parent]))
        {
            $comments = $this->repo->getAll($parent);
            return  new CommentCollection($comments);
        }
        return response([], 403);
    }

    /**
     * Store a comment.
     *
     * @param \App\Http\Requests\CommentRequest $request The request object.
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, $parent)
    {
        if ($request->user()->can('create', [Comment::class, $parent]))
        {
            $comment = $this->repo->create(
                $parent,
                $request->only(['body'])
            );
            return response([
                'data' => [
                    'comment' => [
                        'id' => $comment->id
                        ]
                ]
            ], 201);
        }
        return response([], 403);
    }
    
    /**
     * Show a comment.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     * @param int $comment The comment id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $parent, int $comment)
    {
       $comment = $parent->comments()->findOrFail($comment);
       if($request->user()->can('view', $comment))
        {
            $comment->load('user');
            if($parent instanceof Question)
            {
                $comment->load('rates');
            }
            else if($parent instanceof Post || $parent instanceof Event)
            {
                $comment->load(['replies','replies.user']);
            }
            return response(new CommentResource($comment));
        }
        return response([], 403);
    }

    /**
     * Update a comment.
     *
     * @param \App\Http\Requests\CommentRequest $request The request object.
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     * @param int $comment The comment id.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $parent, int $comment)
    {
        $comment = $parent->comments()->findOrFail($comment);

        if ($request->user()->can('update', $comment)) 
        {
            if ($this->repo->update($comment,  $request->only(['body'])))
                return response([], 204);
        }
        return response([], 403);
    }
    
   /**
     * Destroy a comment.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param mixed $parent The *Post* / *Event* / *Tool* / *Question* object.
     * @param int $comment The comment id.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $parent, int $comment)
    {
        $comment = $parent->comments()->findOrFail($comment);

        if ($request->user()->can('delete', $comment))
        {
            if ($this->repo->delete($comment))
                return response([], 204);
        }
        return response([], 403);
    }
}
