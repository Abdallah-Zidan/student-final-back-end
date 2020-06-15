<?php

namespace App\Repositories\Dashboard;

use App\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class TagRepository
{
	/**
	 * Get all tags.
	 *
	 * @param mixed $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($items = 10)
	{
		if ($items === '*')
		{
			$tags = Tag::all();

			return new LengthAwarePaginator($tags, $tags->count(), $tags->count(), 1, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'page'
			]);
		}
		else
			return Tag::paginate($items);
	}

	/**
	 * Create a tag.
	 *
	 * @param array $data The tag data.
	 *
	 * @return \App\Tag
	 */
	public function create(array $data)
	{
		return Tag::create($data);
	}

	/**
	 * Update an existing tag.
	 *
	 * @param \App\Tag $tag The tag object.
	 * @param array $data The tag data.
	 *
	 * @return void
	 */
	public function update(Tag $tag, array $data)
	{
		$tag->update($data);
	}

	/**
	 * Delete an existing tag.
	 *
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return void
	 */
	public function delete(Tag $tag)
	{
		$tag->delete();
	}
}