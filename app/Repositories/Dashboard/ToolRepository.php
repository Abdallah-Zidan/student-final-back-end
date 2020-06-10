<?php

namespace App\Repositories\Dashboard;

use App\Enums\UserType;
use App\Faculty;
use App\Tag;
use App\Tool;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ToolRepository
{
	/**
	 * Get all tools.
	 *
	 * @param \App\User $user The user object.
	 * @param array $tags The tags array.
	 * @param int $type The tool type.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(User $user, array $tags, int $type = null, int $items = 10)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
			return $this->getAllForMoedrator($user->profileable->faculty, $tags, $type, $items);
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return $this->getAllForAdmin($tags, $type, $items);

		return new LengthAwarePaginator([], 0, 10, 1, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page'
		]);
	}

	/**
	 * Create a tool.
	 *
	 * @param array $data The tool data.
	 *
	 * @return \App\Tool
	 */
	public function create(array $data)
	{
		return Tool::create($data);
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
	 * Attach tool to tag.
	 *
	 * @param \App\Tool $tool The tool object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return void
	 */
	public function attach(Tool $tool, Tag $tag)
	{
		if (!$tool->tags()->find($tag))
			$tool->tags()->attach($tag);
	}

	/**
	 * Detach tool from tag.
	 *
	 * @param \App\Tool $tool The tool object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return void
	 */
	public function detach(Tool $tool, Tag $tag)
	{
		$tool->tags()->detach($tag);
	}

	/**
	 * Get all tools related to the moedrator groups.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param array $tags The tags array.
	 * @param int $type The tool type.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForMoedrator(Faculty $faculty, array $tags, int $type = null, int $items)
	{
		$tools = Tool::with([
			'user',
			'faculty',
			'tags'
		])->where('faculty_id', $faculty->id);

		if (count($tags) > 0)
			$tools = $this->getAllWithTags($tools, $tags);

		if (!is_null($type))
			$tools->where('type', $type);

		return $tools->paginate($items);
	}

	/**
	 * Get all tools.
	 *
	 * @param array $tags The tags array.
	 * @param int $type The tool type.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForAdmin(array $tags, int $type = null, int $items)
	{
		$tools = Tool::with([
			'user',
			'faculty',
			'tags'
		]);

		if (count($tags) > 0)
			$tools = $this->getAllWithTags($tools, $tags);

		if (!is_null($type))
			$tools->where('type', $type);

		return $tools->paginate($items);
	}

	/**
	 * Get all tools related to tags.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query The query object.
	 * @param array $tags The tags array.
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	private function getAllWithTags(Builder $query, array $tags)
	{
		$tags = Tag::whereIn('name', $tags)->get();
		$tag_tools = DB::table('tag_tools')->whereIn('tag_id', $tags->pluck('id'))->get();

		return $query->whereIn('id', $tag_tools->pluck('tool_id'));
	}
}