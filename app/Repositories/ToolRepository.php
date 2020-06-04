<?php

namespace App\Repositories;

use App\Faculty;
use App\Tag;
use App\Tool;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ToolRepository
{
	/**
	 * The tag repository object.
	 *
	 * @var \App\Repositories\TagRepository
	 */
	private $repo;

	/**
	 * Create a new ToolRepository object.
	 *
	 * @param \App\Repositories\TagRepository $repo The tag repository object.
	 */
	public function __construct(TagRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all tools related to the *Faculty* group.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param int $type The tool type.
	 * @param array $tags The tags array.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(Faculty $faculty, int $type, array $tags)
	{
		if (count($tags) > 0)
			return $this->getAllWithTags($faculty, $type, $tags);
		else
			return $this->getAllWithoutTags($faculty, $type);
	}

	/**
	 * Create a tool related to the given user.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\Faculty $faculty The faculty object.
	 * @param array $data The tool data.
	 *
	 * @return \App\Tool
	 */
	public function create(User $user, Faculty $faculty, array $data)
	{
		$tool = $user->tools()->create($data + [
			'faculty_id' => $faculty->id
		]);

		if (array_key_exists('files', $data))
		{
			foreach ($data['files'] as $file)
			{
				$path = Storage::disk('local')->put('files/tools/' . $tool->id, $file);
				$tool->files()->create([
					'name' => $file->getClientOriginalName(),
					'path' => $path,
					'mime' => Storage::mimeType($path)
				]);
			}
		}

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

			$tool->tags()->sync($db_tags->pluck('id'));
		}

		return $tool;
	}

	/**
	 * Update an existing tool.
	 *
	 * @param \App\Tool $tool The tool object.
	 * @param array $data The tool data.
	 *
	 * @return void
	 */
	public function update(Tool $tool, array $data)
	{
		$tool->update($data);

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

		$tool->tags()->sync($db_tags->pluck('id'));
	}

	/**
	 * Delete an existing tool.
	 *
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return void
	 */
	public function delete(Tool $tool)
	{
		$tool->delete();
	}

	/**
	 * Close an existing tool.
	 *
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return void
	 */
	public function close(Tool $tool)
	{
		$tool->update([
			'closed' => true
		]);
	}

	/**
	 * Get all tools related to the *Faculty* group and tags.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param int $type The tool type.
	 * @param array $tags The tags array.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllWithTags(Faculty $faculty, int $type, array $tags)
	{
		$tags = Tag::whereIn('name', $tags)->get();
		$tag_tools = DB::table('tag_tools')->whereIn('tag_id', $tags->pluck('id'))->get();

		$tools = Tool::with([
			'user',
			'faculty',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'files',
			'tags'
		])->where([
			['faculty_id', $faculty->id],
			['type', $type]
		])->whereIn('id', $tag_tools->pluck('tool_id'))->orderBy('created_at', 'desc')->paginate(10);

		return $tools;
	}

	/**
	 * Get all tools related to the *Faculty* group.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param int $type The tool type.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllWithoutTags(Faculty $faculty, int $type)
	{
		$tools = Tool::with([
			'user',
			'faculty',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'files',
			'tags'
		])->where([
			['faculty_id', $faculty->id],
			['type', $type]
		])->orderBy('created_at', 'desc')->paginate(10);

		return $tools;
	}
}