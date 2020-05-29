<?php

namespace App\Repositories;

use App\DepartmentFaculty;
use App\Enums\PostScope;
use App\Enums\UserType;
use App\Faculty;
use App\Post;
use App\University;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class PostRepository
{
	/**
	 * Get all posts related to the *DepartmentFaculty* / *Faculty* / *University* group.
	 *
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($group)
	{
		if ($group instanceof DepartmentFaculty)
			return $this->getAllInDepartment($group);
		else if ($group instanceof Faculty)
			return $this->getAllInFaculty($group);
		else if ($group instanceof University)
			return $this->getAllInUniversity($group);

		return new LengthAwarePaginator([], 0, 10, 1, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page',
		]);
	}

	/**
	 * Create a post related to the given group and user.
	 *
	 * @param \App\User $user The user object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param array $data The post data.
	 *
	 * @return \App\Post
	 */
	public function create(User $user, $group, array $data)
	{
		$post = $user->posts()->create([
			'body' => $data['body'],
			'scopeable_type' => get_class($group),
			'scopeable_id' => $group->id
		] + ($user->type === UserType::getTypeString(UserType::STUDENT) ? [
			'year' => $user->profileable->year
		] : []));

		if (array_key_exists('files', $data))
		{
			$files = $data['files'];

			for ($i = 0; $i < count($files); $i++)
			{
				$path = Storage::disk('local')->put('files/posts/' . $post->id, $files[$i]);
				$mime = Storage::mimeType($path);
				$post->files()->create([
					'path' => $path,
					'mime' => $mime
				]);
			}
		}

		return $post;
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
		$post->update([
			'body' => $data['body']
		]);
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
	 * Get all posts related to the *DepartmentFaculty* group.
	 *
	 * @param \App\DepartmentFaculty $department_faculty The *DepartmentFaculty* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInDepartment(DepartmentFaculty $department_faculty)
	{
		// No need to get the user from the parent, because not all getAll methods will use it.
		$user = request()->user();

		$conditions = [
			['scopeable_type', PostScope::getScopeModel(PostScope::DEPARTMENT)],
			['scopeable_id', $department_faculty->id]
		];

		if ($user->type === UserType::getTypeString(UserType::STUDENT))
		{
			$conditions = array_merge($conditions, [
				['year', $user->profileable->year]
			]);
		}

		$posts = Post::with([
			'user',
			'user.profileable',
			'scopeable',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files'
		])->where($conditions)->orderBy('created_at', 'desc')->paginate(10);
		$posts->load([
			'scopeable.department',
			'scopeable.faculty',
			'scopeable.faculty.university'
		]);

		return $posts;
	}

	/**
	 * Get all posts related to the *Faculty* group.
	 *
	 * @param \App\Faculty $faculty The *Faculty* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInFaculty(Faculty $faculty)
	{
		$posts = Post::with([
			'user',
			'user.profileable',
			'scopeable',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files'
		])->where([
			['scopeable_type', PostScope::getScopeModel(PostScope::FACULTY)],
			['scopeable_id', $faculty->id]
		])->orderBy('created_at', 'desc')->paginate(10);

		return $posts;
	}

	/**
	 * Get all posts related to the *University* group.
	 *
	 * @param \App\University $university The *University* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInUniversity(University $university)
	{
		$posts = Post::with([
			'user',
			'user.profileable',
			'scopeable',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user',
			'files'
		])->where([
			['scopeable_type', PostScope::getScopeModel(PostScope::UNIVERSITY)],
			['scopeable_id', $university->id]
		])->orderBy('created_at', 'desc')->paginate(10);

		return $posts;
	}
}