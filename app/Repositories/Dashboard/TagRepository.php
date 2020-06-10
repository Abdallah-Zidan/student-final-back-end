<?php

namespace App\Repositories\Dashboard;

use App\Tag;

class TagRepository
{
	/**
	 * Get all tags.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(int $items = 10)
	{
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