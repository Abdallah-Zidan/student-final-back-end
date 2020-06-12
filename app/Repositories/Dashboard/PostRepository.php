<?php

namespace App\Repositories\Dashboard;

use App\DepartmentFaculty;
use App\Enums\PostScope;
use App\Enums\UserType;
use App\Faculty;
use App\Post;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PostRepository
{
	/**
	 * Get all posts.
	 *
	 * @param \App\User $user The user object.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(User $user, int $items = 10)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
			return $this->getAllForMoedrator($user->profileable->faculty, $items);
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return $this->getAllForAdmin($items);

		return new LengthAwarePaginator([], 0, 10, 1, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page'
		]);
	}

	/**
	 * Create a post.
	 *
	 * @param array $data The post data.
	 *
	 * @return \App\Post
	 */
	public function create(array $data)
	{
		return Post::create($data);
	}

	/**
	 * Update an existing post.
	 *
	 * @param \App\Post $post The post object.
	 * @param array $data The post data.
	 *
	 * @return void
	 */
	public function update(Post $post, array $data)
	{
		$post->update($data);
	}

	/**
	 * Delete an existing post.
	 *
	 * @param \App\Post $post The post object.
	 *
	 * @return void
	 */
	public function delete(Post $post)
	{
		$post->delete();
	}

	/**
	 * Get all posts related to the moedrator groups.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForMoedrator(Faculty $faculty, int $items)
	{
		$departments = DepartmentFaculty::where('faculty_id', $faculty->id)->get();

		$posts = Post::with([
			'user',
			'scopeable'
		])->where(function ($query) use ($departments) {
			$query->where('scopeable_type', PostScope::getScopeModel(PostScope::DEPARTMENT))
				  ->whereIn('scopeable_id', $departments->pluck('id'));
		})->orWhere([
			['scopeable_type', PostScope::getScopeModel(PostScope::FACULTY)],
			['scopeable_id', $faculty->id]
		])->orWhere([
			['scopeable_type', PostScope::getScopeModel(PostScope::UNIVERSITY)],
			['scopeable_id', $faculty->university->id]
		])->paginate($items);

		$posts->where('scopeable_type', PostScope::getScopeModel(PostScope::DEPARTMENT))->load([
			'scopeable.department',
			'scopeable.faculty.university'
		]);

		return $posts;
	}

	/**
	 * Get all posts.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForAdmin(int $items)
	{
		$posts = Post::with([
			'user',
			'scopeable'
		])->paginate($items);

		$posts->where('scopeable_type', PostScope::getScopeModel(PostScope::DEPARTMENT))->load([
			'scopeable.department',
			'scopeable.faculty.university'
		]);

		return $posts;
	}
}