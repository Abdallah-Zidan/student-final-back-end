<?php

namespace App\Repositories;

use App\Tag;
use App\Tutorial;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TutorialRepository
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
	public function getAll(string $tags =null)
	{
		$tags = array_filter(array_map('trim', explode(",", $tags)));

		if (count($tags)) {
			return $this->filter($tags);
		}

		return Tutorial::with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies.user',
			'tags',
			'files'
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
		$questions_ids = DB::table('tag_tutorials')->whereIn('tag_id', $tags_ids)->get()->pluck('tutorial_id');
		$tutorials = Tutorial::whereIn('id', $questions_ids)->with([
			'user',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'comments.rates',
			'comments.user',
			'tags',
			'files'
		])->orderBy('created_at', 'desc')->paginate(10);
		return $tutorials;
	}

	/**
	 * Create a question related to the given user.
	 *
	 * @param \App\User $user The user object.
	 * @param array $data The question data.
	 *
	 * @return \App\Question
	 */
	public function create(array $data, User $user)
	{
		$tutorial = Tutorial::create([ 'body' => $data['body'], 'user_id'=> $user->id]);
		$this->attachTags($tutorial, $data['tags']);

		if (array_key_exists('files', $data)) {
            foreach ($data['files'] as $file) {
                $path = Storage::disk('local')->put('files/posts/' . $tutorial->id, $file);
                $tutorial->files()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => Storage::mimeType($path)
                ]);
            }
        }	
		return $tutorial;
	}

	/**
	 * Attach tags with Questions
	 *
	 * @param Question $question
	 * @param array $tag_names
	 * @return void
	 */
	private function attachTags(Tutorial $tutorial, array $tag_names)
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
					$new_tag = $this->repo->create(['name'=>$tag_name]);
					array_push($db_tag_ids, $new_tag->id);
				}
			}
		}
		$tutorial->tags()->sync($db_tag_ids);
	}

	/**
	 * Update an existing question.
	 *
	 * @param \App\Question $question The question object.
	 * @param array $data The question data.
	 *
	 * @return Question
	 */
	public function update(Tutorial $tutorial, array $data)
	{
		$tutorial->update(['body' => $data['body']]);
		if (array_key_exists('tags', $data)) {
			$this->attachTags($tutorial, $data['tags']);
		}
		if (array_key_exists('files', $data)) {
			$tutorial->files->each->delete();
            foreach ($data['files'] as $file) {
                $path = Storage::disk('local')->put('files/posts/' . $tutorial->id, $file);
                $tutorial->files()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => Storage::mimeType($path)
                ]);
            }
        }	
		return $tutorial;
	}
	
	/**
	 * Delete an existing question.
	 *
	 * @param \App\Question $question The question object.
	 *
	 * @return void
	 */
	public function delete(Tutorial $tutorial)
	{
		$tutorial->tags()->detach();
		$tutorial->delete();
	}
}
