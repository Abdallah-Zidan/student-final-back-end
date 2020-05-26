<?php

namespace App\Repositories;

use App\DepartmentFaculty;
use App\Enums\PostScope;
use App\Enums\UserType;
use App\Post;
use App\User;

class PostRepository
{
	public function getPostsFor(User $current_user, $department_faculty_id, string $scope)
	{
		if ($scope == PostScope::FACULTY)
		{
			if ($current_user->type === UserType::getTypeString(UserType::MODERATOR))
				return $this->getPostsForModeratorInSameFaculty($current_user, $department_faculty_id);
			else
				return $this->getPostsForUserInSameFaculty($current_user, $department_faculty_id);
		}
		else if ($scope == PostScope::YEAR)
		{
			if ($current_user->type === UserType::getTypeString(UserType::STUDENT))
				return $this->getPostsForStudentInSameYear($current_user, $department_faculty_id);
		}
		else if ($scope == PostScope::DEPARTMENT)
		{
			if ($current_user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
				return $this->getPostsForTeachingStaffInSameDepartment($current_user, $department_faculty_id);
		}
		else if ($scope == PostScope::ALL)
		{
			if ($current_user->type === UserType::getTypeString(UserType::MODERATOR))
				return $this->getPostsForModerator($current_user);
			else if ($current_user->type === UserType::getTypeString(UserType::ADMIN))
				return $this->getPostsForAdmin();
		}

		return [];
	}

	public function getPostsForUserInSameFaculty(User $user, $id)
	{
		$department_faculty = $user->departmentFaculties()->find($id);

		if ($department_faculty)
		{
			$department_faculties = DepartmentFaculty::where('faculty_id', $department_faculty->faculty_id)->get();

			$posts = Post::with([
				'user',
				'user.profileable',
				'departmentFaculty.department',
				'departmentFaculty.faculty',
				'departmentFaculty.faculty.university',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->whereIn('department_faculty_id', $department_faculties->pluck('id'))->orderBy('created_at')->paginate(10);

			return $posts;
		}

		return [];
	}

	public function getPostsForStudentInSameYear(User $student, $id)
	{
		// Get all posts then paginate because not all posts belong to students in the same year
		$posts = Post::with('user.profileable')->where('department_faculty_id', $id)->orderBy('created_at')->get()->filter(function ($post) use ($student) {
			return $post->user->profileable->year === $student->profileable->year;
		});

		$posts = Post::with('user.profileable')->whereIn('id', $posts->pluck('id'))->orderBy('created_at')->paginate(10);

		$posts = $posts->load([
			'departmentFaculty.department',
			'departmentFaculty.faculty',
			'departmentFaculty.faculty.university',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user'
		]);

		return $posts;
	}

	public function getPostsForTeachingStaffInSameDepartment(User $teaching_staff, $id)
	{
		$department_faculty = $teaching_staff->departmentFaculties()->find($id);

		if ($department_faculty)
		{
			$posts = Post::with([
				'user',
				'user.profileable',
				'departmentFaculty.department',
				'departmentFaculty.faculty',
				'departmentFaculty.faculty.university',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->where('department_faculty_id', $department_faculty->id)->orderBy('created_at')->paginate(10);

			return $posts;
		}

		return [];
	}

	public function getPostsForModeratorInSameFaculty(User $moderator, $id)
	{
		$department_faculties = DepartmentFaculty::where('faculty_id', $moderator->profileable->faculty->id)->get();

		$department_faculty = $department_faculties->find($id);

		if ($department_faculty)
		{
			$department_faculties = DepartmentFaculty::where('faculty_id', $department_faculty->faculty_id)->get();

			$posts = Post::with([
				'user',
				'user.profileable',
				'departmentFaculty.department',
				'departmentFaculty.faculty',
				'departmentFaculty.faculty.university',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->whereIn('department_faculty_id', $department_faculties->pluck('id'))->orderBy('created_at')->paginate(10);

			return $posts;
		}

		return [];
	}

	public function getPostsForModerator(User $moderator)
	{
		$department_faculties = DepartmentFaculty::where('faculty_id', $moderator->profileable->faculty->id)->get();

		if ($department_faculties)
		{
			$posts = Post::with([
				'user',
				'user.profileable',
				'departmentFaculty.department',
				'departmentFaculty.faculty',
				'departmentFaculty.faculty.university',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user'
			])->whereIn('department_faculty_id', $department_faculties->pluck('id'))->orderBy('created_at')->paginate(10);

			return $posts;
		}

		return [];
	}

	public function getPostsForAdmin()
	{
		$posts = Post::with([
			'user',
			'user.profileable',
			'departmentFaculty.department',
			'departmentFaculty.faculty',
			'departmentFaculty.faculty.university',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user'
		])->orderBy('created_at')->paginate(10);

		return $posts;
	}
}