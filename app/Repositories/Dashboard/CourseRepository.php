<?php

namespace App\Repositories\Dashboard;

use App\Course;
use App\DepartmentFaculty;

class CourseRepository
{
	/**
	 * Get all courses.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(int $items = 10)
	{
		return Course::with([
			'departmentFaculties.faculty',
			'departmentFaculties.department'
		])->paginate($items);
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
		if (!$course->departmentFaculties()->find($department_faculty))
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