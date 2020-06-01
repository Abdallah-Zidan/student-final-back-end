<?php

namespace App\Repositories;

use App\Tool;
use App\User;
use Illuminate\Support\Facades\Storage;

class ToolRepository
{
	/**
	 * Get all tools related to the *Faculty* group.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($faculty_id)
	{
		$tools = Tool::with([
			'user',
			'user.profileable',
			'faculty',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files',
			'tags'
		])->where('faculty_id', $faculty_id)->orderBy('created_at', 'desc')->paginate(10);

		return $tools;
	}

	/**
	 * Create a tool related to the given user.
	 *
	 * @param \App\User $user The user object.
	 * @param array $data The tool data.
	 *
	 * @return \App\Tool
	 */
	public function create(User $user, array $data)
	{
		$faculty_id = $user->departmentFaculties()->first()->faculty->id;
		$tool = $user->tools()->create([
			'title' => $data['title'],
			'body' => $data['body'],
			'type' => $data['type'],
			'faculty_id' => $faculty_id
		]);

		if (array_key_exists('files', $data))
		{
			$files = $data['files'];

			for ($i = 0; $i < count($files); $i++)
			{
				$path = Storage::disk('local')->put('files/tools/' . $tool->id, $files[$i]);
				$mime = Storage::mimeType($path);
				$tool->files()->create([
					'path' => $path,
					'mime' => $mime
				]);
			}
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
		$tool->update([
			'title' => $data['title'],
			'body' => $data['body']
		]);
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
}