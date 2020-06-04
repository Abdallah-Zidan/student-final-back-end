<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionCollection;
use App\Http\Resources\QuestionResource;
use App\Question;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * The question repository object.
     *
     * @var \App\Repositories\QuestionRepository
     */
    private $repo;

    /**
     * Create a new QuestionController object.
     *
     * @param \App\Repositories\QuestionRepository $repo The question repository object.
     */
    public function __construct(QuestionRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get all questions.
     * 
     * @param \App\Http\Requests\QuestionRequest $request The request object.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuestionRequest $request)
    {
        $tags = array_filter(array_map('trim', explode(',', $request->tags)));
        $questions = $this->repo->getAll($tags);

        return new QuestionCollection($questions);
    }

    /**
     * Store a question.
     *
     * @param \App\Http\Requests\QuestionRequest $request The request object.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $tags = array_filter(array_map('trim', $request->tags));
        $question = $this->repo->create($request->user(), array_merge($request->only(['title', 'body']), $tags));

        return response([
            'data' => [
                'question' => [
                    'id' => $question->id
                ]
            ]
        ], 201);
    }

    /**
     * Show a question.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param \App\Question $question The question object.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Question $question)
    {
        $question->load([
            'user',
            'comments' => function ($query) { $query->orderBy('created_at'); },
            'comments.user',
            'tags'
        ]);

        return response([
            'data' => [
                'question' => new QuestionResource($question)
            ]
        ]);
    }

    /**
     * Update a question.
     *
     * @param \App\Http\Requests\QuestionRequest $request The request object.
     * @param \App\Question $question The question object.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        if ($request->user()->can('update', $question))
        {
            $tags = array_filter(array_map('trim', $request->tags));
            $this->repo->update($question, array_merge($request->only(['title', 'body']), $tags));

            return response([], 204);
        }

        return response([], 403);
    }

    /**
     * Destroy a question.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param \App\Question $question The question object.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Question $question)
    {
        if ($request->user()->can('delete', $question))
        {
            $this->repo->delete($question);

            return response([], 204);
        }

        return response([], 403);
    }
}