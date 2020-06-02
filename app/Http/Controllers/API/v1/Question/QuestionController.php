<?php

namespace App\Http\Controllers\API\v1\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\TagResource;
use App\Question;
use App\QuestionTag;
use App\Repositories\QuestionRepository;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    private $repo;

    public function __construct(QuestionRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->repo->getAll($request->tags);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {

        $question = $this->repo->create($request->title, $request->body, $request->tags, $request->user()->id);
        return response([
            'data' => [
                'question' => $question->id,
                'tags' => TagResource::collection( $question->tags)
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question = $this->repo->update($question, $request->title, $request->body, $request->tags);
        return response([
            'data' => [
                'tags' => TagResource::collection( $question->tags)
            ]
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->delete($question);
        return response([], 204);
    }
}
