<?php

namespace App\Repositories;

use App\Enums\PostScope;
use App\Enums\UserType;
use App\Post;
use App\User;

class PostRepository
{
	public function getPostsFor(User $current_user, string $scope, $scope_id)
	{
		if ($scope == PostScope::DEPARTMENT)
			return $this->getPostsInDepartment($current_user, $scope_id);
		if ($scope == PostScope::FACULTY)
			return $this->getPostsInFaculty($current_user, $scope_id);
		else if ($scope == PostScope::UNIVERSITY)
			return $this->getPostsInUniversity($current_user, $scope_id);

		return [];
	}

	public function create(User $current_user, array $data)
	{
		if ($this->checkPostScope($current_user, $data['scope'], $data['scope_id']))
		{
			return $current_user->posts()->create([
				'body' => $data['body'],
				'scopeable_type' => PostScope::getScopeModel($data['scope']),
				'scopeable_id' => $data['scope_id']
			]);
		}

		return false;
	}

	public function update(User $current_user, Post $post, array $data)
	{
		if ($post->user->id === $current_user->id)
		{
			$post->update([
				'body' => $data['body']
			]);

			return true;
		}

		return false;
	}

	public function delete(User $current_user, Post $post)
	{
		if ($post->user->id === $current_user->id)
		{
			$post->delete();

			return true;
		}

		return false;
	}

	private function getPostsInDepartment(User $user, $scope_id)
	{
		if ($this->checkPostScope($user, PostScope::DEPARTMENT, $scope_id))
		{
			// Get all posts then paginate because not all posts belong to students in the same year
			$posts = Post::with('user.profileable')->where([
				['scopeable_type', PostScope::getScopeModel(PostScope::DEPARTMENT)],
				['scopeable_id', $scope_id]
			])->get()->filter(function ($post) use ($user) {
				if ($user->type === UserType::getTypeString(UserType::STUDENT) &&
					$post->user->type === UserType::getTypeString(UserType::STUDENT))
					return $post->user->profileable->year === $user->profileable->year;
				else
					return true;
			});

			$posts = Post::with([
				'user',
				'user.profileable',
				'scopeable',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->whereIn('id', $posts->pluck('id'))->orderBy('created_at')->paginate(10)->load([
				'scopeable.department',
				'scopeable.faculty',
				'scopeable.faculty.university'
			]);

			return $posts;
		}

		return false;
	}

	private function getPostsInFaculty(User $user, $scope_id)
	{
		if ($this->checkPostScope($user, PostScope::FACULTY, $scope_id))
		{
			$posts = Post::with([
				'user',
				'user.profileable',
				'scopeable',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->where([
				['scopeable_type', PostScope::getScopeModel(PostScope::FACULTY)],
				['scopeable_id', $scope_id]
			])->orderBy('created_at')->paginate(10);

			return $posts;
		}

		return false;
	}

	private function getPostsInUniversity(User $user, $scope_id)
	{
		if ($this->checkPostScope($user, PostScope::UNIVERSITY, $scope_id))
		{
			$posts = Post::with([
				'user',
				'user.profileable',
				'scopeable',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->where([
				['scopeable_type', PostScope::getScopeModel(PostScope::UNIVERSITY)],
				['scopeable_id', $scope_id]
			])->orderBy('created_at')->paginate(10);

			return $posts;
		}

		return false;
	}

	private function checkPostScope(User $user, string $scope, int $scope_id)
	{
		if ($scope == PostScope::DEPARTMENT)
			return $user->departmentFaculties()->find($scope_id) ? true : false;
		if ($scope == PostScope::FACULTY)
		{
			return $user->departmentFaculties->load('faculty')->first(function ($department_faculty) use ($scope_id) {
				return $department_faculty->faculty->id == $scope_id;
			}) ? true : false;
		}
		else if ($scope == PostScope::UNIVERSITY)
		{
			return $user->departmentFaculties->load('faculty.university')->first(function ($department_faculty) use ($scope_id) {
				return $department_faculty->faculty->university->id == $scope_id;
			}) ? true : false;
		}

		return false;
	}
}