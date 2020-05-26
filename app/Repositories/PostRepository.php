<?php

namespace App\Repositories;

use App\DepartmentFaculty;
use App\Enums\UserType;
use App\Faculty;
use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class PostRepository
{
	public function getPostsFor(User $current_user, string $scope)
	{
		if ($scope === 'faculty')
			return $this->getPostsForUserInSameFaculty($current_user);
		else if ($scope === 'year')
		{
			if ($current_user->type === UserType::getTypeString(UserType::STUDENT))
				return $this->getPostsForStudentInSameYear($current_user);
		}
		else if ($scope === 'department')
		{
			if ($current_user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
				return $this->getPostsForTeachingStaffInSameDepartment($current_user);
		}
	}

	public function getPostsForUserInSameFaculty(User $user)
	{
		$department_faculties = $user->departmentFaculties;

		$faculties = Faculty::whereIn('id', $department_faculties->pluck('faculty_id'))->get();

		$department_faculties = DepartmentFaculty::whereIn('faculty_id', $faculties->pluck('id'))->get();

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
		])->whereIn('department_faculty_id', $department_faculties->pluck('id'))->orderBy('created_at')->get();

		return $this->groupPosts($posts);
	}

	public function getPostsForStudentInSameYear(User $student)
	{
		$department_faculties = $student->departmentFaculties;

		$posts = Post::with('user.profileable')->whereIn('department_faculty_id', $department_faculties->pluck('id'))->orderBy('created_at')->get()->filter(function ($post) use ($student) {
			return $post->user->profileable->year === $student->profileable->year;
		});

		$posts = $posts->load([
			'departmentFaculty.department',
			'departmentFaculty.faculty',
			'departmentFaculty.faculty.university',
			'comments' => function ($query) { $query->orderBy('created_at'); },
			'comments.user',
			'comments.replies' => function ($query) { $query->orderBy('created_at'); },
			'comments.replies.user'
		]);

		return $this->groupPosts($posts);
	}

	public function getPostsForTeachingStaffInSameDepartment(User $teaching_staff)
	{
		$department_faculties = $teaching_staff->departmentFaculties;

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
		])->whereIn('department_faculty_id', $department_faculties->pluck('id'))->orderBy('created_at')->get();

		return $this->groupPosts($posts);
	}

	private function groupPosts(Collection $posts)
	{
		return $posts->groupBy(function ($item) {
			return $item['departmentFaculty']['department']['name'] . ' - ' . $item['departmentFaculty']['faculty']['name'];
		});
	}
}