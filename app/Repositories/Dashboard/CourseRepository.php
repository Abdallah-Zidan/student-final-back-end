<?php

namespace App\Repositories\Dashboard;

use App\Course;
use App\DepartmentFaculty;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class CourseRepository
{
	/**
	 * Get all courses.
	 *
	 * @param mixed $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($items = 10)
	{
		if ($items === '*')
		{
			$courses = Course::with([
				'departmentFaculties.faculty',
				'departmentFaculties.department'
			])->get();

			return new LengthAwarePaginator($courses, $courses->count(), $courses->count(), 1, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'page'
			]);
		}
		else
			return Course::paginate($items);
	}

	/**
	 * Create a course.
	 *
	 * @param array $data The course data.
	 *
	 * @return \App\Course
	 */
	public function create(array $data)
	{
		return Course::create($data);
	}

	/**
	 * Update an existing course.
	 *
	 * @param \App\Course $course The course object.
	 * @param array $data The course data.
	 *
	 * @return void
	 */
	public function update(Course $course, array $data)
	{
		$course->update($data);
	}

	/**
	 * Delete an existing course.
	 *
	 * @param \App\Course $course The course object.
	 *
	 * @return void
	 */
	public function delete(Course $course)
	{
		$course->delete();
	}

	/**
	 * Attach course to department_faculty.
	 *
	 * @param \App\Course $course The course object.
	 * @param \App\DepartmentFaculty $department_faculty The department_faculty object.
	 *
	 * @return void
	 */
	public function attach(Course $course, DepartmentFaculty $department_faculty)
	{
		if (!$course->departmentFaculties()->find($department_faculty->id))
			$course->departmentFaculties()->attach($department_faculty);
	}

	/**
	 * Detach course from department_faculty.
	 *
	 * @param \App\Course $course The course object.
	 * @param \App\DepartmentFaculty $department_faculty The department_faculty object.
	 *
	 * @return void
	 */
	public function detach(Course $course, DepartmentFaculty $department_faculty)
	{
		$course->departmentFaculties()->detach($department_faculty);
	}
}