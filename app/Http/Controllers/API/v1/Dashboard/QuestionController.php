<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionCollection;
use App\Http\Resources\QuestionResource;
use App\Question;
use App\Repositories\Dashboard\QuestionRepository;
use App\Tag;
use App\User;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
	/**
	 * The question repository object.
	 *
	 * @var \App\Repositories\Dashboard\QuestionRepository
	 */
	private $repo;

	/**
	 * Create a new QuestionController object.
	 *
	 * @param \App\Repositories\Dashboard\QuestionRepository $repo The question repository object.
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
		if ($request->user()->can('viewAny', Question::class))
		{
			$items = intval($request->items) ?: 10;
			$tags = array_filter(array_map('trim', explode(',', $request->tags)));
			$questions = $this->repo->getAll($tags, $items);

			return new QuestionCollection($questions);
		}

		return response([], 403);
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
		$user = $request->user();

		if ($user->type === UserType::getTypeString(UserType::ADMIN))
			User::findOrFail($request->user_id);

		if ($user->can('create', Question::class))
		{
			$data = $request->only(['title', 'body']) + ($user->type === UserType::getTypeString(UserType::ADMIN) ? $request->only(['user_id']) : [
				'user_id' => $user->id
			]);
			$question = $this->repo->create($data);

			return response([
				'data' => [
					'question' => [
						'id' => $question->id
					]
				]
			], 201);
		}

		return response([], 403);
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
		if ($request->user()->can('view', $question))
		{
			$question->load([
				'user',
				'tags'
			]);

			return response([
				'data' => [
					'question' => new QuestionResource($question)
				]
			]);
		}

		return response([], 403);
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
		$user = $request->user();

		if ($user->type === UserType::getTypeString(UserType::ADMIN))
			User::findOrFail($request->user_id);

		if ($user->can('update', $question))
		{
			$data = $request->only(['title', 'body']) + ($user->type === UserType::getTypeString(UserType::ADMIN) ? $request->only(['user_id']) : []);
			$this->repo->update($question, $data);

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

	/**
	 * Attach question to tag.
	 *
	 * @param \App\Http\Requests\QuestionRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attach(QuestionRequest $request)
	{
		$question = Question::findOrFail($request->question_id);
		$tag = Tag::findOrFail($request->tag_id);

		if ($request->user()->can('attach', [Question::class, $question, $tag]))
		{
			$this->repo->attach($question, $tag);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Detach question from tag.
	 *
	 * @param \App\Http\Requests\QuestionRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function detach(QuestionRequest $request)
	{
		$question = Question::findOrFail($request->question_id);
		$tag = Tag::findOrFail($request->tag_id);

		if ($request->user()->can('detach', [Question::class, $question, $tag]))
		{
			$this->repo->detach($question, $tag);

			return response([], 204);
		}

		return response([], 403);
	}
}