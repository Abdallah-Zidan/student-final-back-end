<?php

namespace App\Repositories;

use App\Question;
use App\Tag;
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
	 * Get all Questions.
	 *
	 * @param array $tags The tags array.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(array $tags = [])
	{
		$tags = array_filter(array_map('trim', explode(",", $tags)));

		if (count($tags)) {
			return $this->filter($tags);
		}

		return Question::with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'tags',
		])->orderBy('created_at' , 'desc')->paginate(10);
	}

	/**
	 * Fillter Questions with tags
	 *
	 * @param array $tags
	 * @return Question $question
	 */
	private function filter(array $tags)
	{
		$tags_ids = Tag::whereIn('name', $tags)->get()->pluck('id');
		$questions_ids = DB::table('question_tags')->whereIn('tag_id', $tags_ids)->get()->pluck('question_id');
		$questions = Question::whereIn('id', $questions_ids)->with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'tags'
		])->orderBy('created_at', 'desc')->paginate(10);
		return $questions;
	}

	/**
	 * Create a question related to the given user.
	 *
	 * @param \App\User $user The user object.
	 * @param array $data The question data.
	 *
	 * @return \App\Question
	 */
	public function create(array $data)
	{
		$question = Question::create(['title' => $data['title'], 'body' => $data['body'], 'user_id' => request()->user()->id]);
		$this->attachTags($question, $data['tags']);
		return $question;
	}

	/**
	 * Attach tags with Questions
	 *
	 * @param Question $question
	 * @param array $tag_names
	 * @return void
	 */
	private function attachTags(Question $question, array $tag_names)
	{
		$tag_names = array_filter(array_map('trim', $tag_names));
		$db_tags = Tag::whereIn('name', $tag_names);
		$db_tag_names = $db_tags->pluck('name')->toArray();
		$db_tag_ids = $db_tags->pluck('id')->toArray();
		if (count($db_tag_names) != count($tag_names)) 
		{
			foreach ($tag_names as $tag_name) 
			{
				if (!in_array($tag_name, $db_tag_names)) 
				{
					$new_tag = $this->repo->create(['name',$tag_name]);
					array_push($db_tag_ids, $new_tag->id);
				}
			}
		}
		$question->tags()->sync($db_tag_ids);
	}

	/**
	 * Update an existing question.
	 *
	 * @param \App\Question $question The question object.
	 * @param array $data The question data.
	 *
	 * @return Question
	 */
	public function update(Question $question, array $data)
	{
		$question->update(['title' => $data['title'], 'body' => $data['body']]);
		$this->attachTags($question, $data['tags']);
		return $question;
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
		$question->tags()->detach();
		$question->delete();
	}
}
