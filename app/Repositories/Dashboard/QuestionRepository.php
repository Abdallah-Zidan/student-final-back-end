<?php

namespace App\Repositories\Dashboard;

use App\Question;
use App\Tag;
use Illuminate\Support\Facades\DB;

class QuestionRepository
{
	/**
	 * Get all questions.
	 *
	 * @param array $tags The tags array.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(array $tags, int $items = 10)
	{
		if (count($tags) > 0)
			return $this->getAllWithTags($tags, $items);
		else
			return $this->getAllWithoutTags($items);
	}

	/**
	 * Create a question.
	 *
	 * @param array $data The question data.
	 *
	 * @return \App\Question
	 */
	public function create(array $data)
	{
		return Question::create($data);
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
	 * Attach question to tag.
	 *
	 * @param \App\Question $question The question object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return void
	 */
	public function attach(Question $question, Tag $tag)
	{
		if (!$question->tags()->find($tag))
			$question->tags()->attach($tag);
	}

	/**
	 * Detach question from tag.
	 *
	 * @param \App\Question $question The question object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return void
	 */
	public function detach(Question $question, Tag $tag)
	{
		$question->tags()->detach($tag);
	}

	/**
	 * Get all questions related to tags.
	 *
	 * @param array $tags The tags array.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllWithTags(array $tags, int $items)
	{
		$tags = Tag::whereIn('name', $tags)->get();
		$question_tags = DB::table('question_tags')->whereIn('tag_id', $tags->pluck('id'))->get();

		return Question::with([
			'user',
			'tags'
		])->whereIn('id', $question_tags->pluck('question_id'))->paginate($items);
	}

	/**
	 * Get all questions.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllWithoutTags(int $items)
	{
		return Question::with([
			'user',
			'tags'
		])->paginate($items);
	}
}