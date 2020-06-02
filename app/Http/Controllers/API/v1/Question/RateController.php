<?php

namespace App\Http\Controllers\API\v1\Question;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Repositories\RateRepository;
use App\User;
use Illuminate\Http\Request;

class RateController extends Controller
{
    private $repo;

    public function __construct(RateRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateRequest $request, Comment $comment)
    {
        $this->repo->create($comment, $request->rate, $request->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Comment $comment
     * @return void
     */
    public function update(RateRequest $request, Comment $comment)
    {
        $this->repo->update($comment, $request->rate, $request->user());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Comment $comment
     * @return void
     */
    public function destroy(Request $request, Comment $comment)
    {
        $this->repo->delete($comment, $request->user());
    }
}
