<?php

namespace App\Repositories;

use App\Http\Resources\QuestionCollection;
use App\Question;
use App\Tag;
use Illuminate\Support\Facades\DB;

class QuestionRepository
{
	private $repo;
	public function __construct(TagRepository $repo)
	{
		$this->repo = $repo;
	}

	public function getAll($tags = null)
	{
		if ($tags) {
			return new QuestionCollection($this->filter($tags));
		}
		return new QuestionCollection(Question::paginate(20));
	}

	private function filter($tags)
	{
		$tags = explode(" ", $tags);
		$tags_ids = Tag::whereIn('name', $tags)->get()->pluck('id');
		$questions_ids = DB::table('question_tags')->whereIn('tag_id', $tags_ids)->get()->pluck('question_id');
		$questions = Question::whereIn('id', $questions_ids)->with(['tags'])
			->orderBy('created_at', 'desc')->paginate(20);
		return $questions;
	}

	public function create($title, $body, $tag_names, $user_id)
	{
		$question = Question::create(['title' => $title, 'body' => $body, 'user_id' => $user_id]);
		$this->attachTags($question, $tag_names);
		return $question;
	}

	private function attachTags($question, $tag_names) 
	{
		$db_tags = Tag::whereIn('name', $tag_names);
		$db_tag_names = $db_tags->pluck('name')->toArray();
		$db_tag_ids = $db_tags->pluck('id')->toArray();
		if (count($db_tag_names) != count($tag_names)) {
			foreach ($tag_names as $tag_name) {
				if (!in_array($tag_name, $db_tag_names)) {
					$new_tag = $this->repo->create($tag_name);
					array_push($db_tag_ids, $new_tag->id);
				}
			}
		}
		$question->tags()->sync($db_tag_ids);
	}

	public function update(Question $question, $title, $body, $tag_names)
	{
		$question->update(['title' => $title, 'body' => $body]);
		$this->attachTags($question, $tag_names);
		return $question;
	}

	public function delete(Question $question)
	{
		$question->tags()->detach();
		$question->delete();
	}
}
