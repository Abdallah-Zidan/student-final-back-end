<?php

namespace App\Http\Controllers\API\v1;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Question;
use App\Repositories\RateRepository;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * The rate repository object.
     *
     * @var \App\Repositories\RateRepository
     */
    private $repo;

    /**
     * Create a new RateController object.
     *
     * @param \App\Repositories\RateRepository $repo The rate repository object.
     */
    public function __construct(RateRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Store a rate.
     *
     * @param \App\Http\Requests\RateRequest $request The request object.
     * @param \App\Comment $comment The comment object.
     *
     * @return \Illuminate\Http\Response
     */
     public function store(RateRequest $request, Comment $comment)
    {
        if ($comment->parent instanceof Question) 
        {
            $this->repo->create($comment, $request->only('rate'));
            return response([], 201);
        }
        return response([], 403);
    }

    /**
     * Update a rate.
     *
     * @param \App\Http\Requests\RateRequest $request The request object.
     * @param \App\Comment $comment The comment object.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RateRequest $request, Comment $comment)
    {
       if ($comment->parent instanceof Question) 
        {
            if ($this->repo->update($comment, $request->only('rate')))
                return response([], 204);
        }
        return response([], 403);
    }

 /**
  * Destroy a rate.
  *
  * @param \Illuminate\Http\Request $request The request object.
  * @param \App\Comment $comment The comment object.
  *
  * @return \Illuminate\Http\Response
  */
    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->parent instanceof Question) 
        {
            $this->repo->delete($comment);
            return response([], 204);
        }
        return response([], 403);
    }
}