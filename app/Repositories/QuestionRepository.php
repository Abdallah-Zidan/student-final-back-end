<?php

namespace App\Repositories;

use App\Question;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\DB;

class QuestionRepository
{
	/**
	 * The tag repository object.
	 *
	 * @var \App\Repositories\TagRepository
	 */
	private $repo;

	/**
	 * Create a new QuestionRepository object.
	 *
	 * @param \App\Repositories\TagRepository $repo The tag repository object.
	 */
	public function __construct(TagRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all tags.
	 *
	 * @param array $tags The tags array.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(array $tags)
	{
		if (count($tags) > 0)
			return $this->getAllWithTags($tags);
		else
			return $this->getAllWithoutTags();
	}

	/**
	 * Create a question related to the given user.
	 *
	 * @param \App\User $user The user object.
	 * @param array $data The question data.
	 *
	 * @return \App\Question
	 */
	public function create(User $user, array $data)
	{
		$question = Question::create($data + [
			'user_id' => $user->id
		]);

		if (count($data['tags']) > 0)
		{
			$tags = $data['tags'];
			$db_tags = Tag::whereIn('name', $tags)->get();

			if ($db_tags->count() < count($tags))
			{
				foreach ($tags as $tag)
				{
					if (!$db_tags->contains('name', $tag))
					{
						$tag = $this->repo->create([
							'name' => $tag
						]);
						$db_tags->push($tag);
					}
				}
			}

			$question->tags()->sync($db_tags->pluck('id'));
		}

		return $question;
	}

	/**
	 * Update an existing question.
	 *
	 * @param \App\Question $question The question object.
	 * @param array $data The question data.
	 *
	 * @return void
	 */
	public function update(Question $question, array $data)
	{
		$question->update($data);

		$tags = $data['tags'];
		$db_tags = Tag::whereIn('name', $tags)->get();

		foreach ($tags as $tag)
		{
			if (!$db_tags->contains('name', $tag))
			{
				$tag = $this->repo->create([
					'name' => $tag
				]);
				$db_tags->push($tag);
			}
		}

		$question->tags()->sync($db_tags->pluck('id'));
	}

	/**
	 * Delete an existing question.
	 *
	 * @param \App\Question $question The question object.
	 *
	 * @return void
	 */
	public function delete(Question $question)
	{
		$question->delete();
	}

	/**
	 * Get all questions related to tags.
	 *
	 * @param array $tags The tags array.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllWithTags(array $tags)
	{
		$tags = Tag::whereIn('name', $tags)->get();
		$question_tags = DB::table('question_tags')->whereIn('tag_id', $tags->pluck('id'))->get();

		$questions = Question::with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'tags'
		])->whereIn('id', $question_tags->pluck('question_id'))->orderBy('created_at', 'desc')->paginate(10);

		return $questions;
	}

	/**
	 * Get all questions.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllWithoutTags()
	{
		return Question::with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'tags'
		])->orderBy('created_at', 'desc')->paginate(10);
	}
}