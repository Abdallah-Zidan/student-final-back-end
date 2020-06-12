<?php

namespace App\Repositories\Dashboard;

use App\Department;
use App\Faculty;

class DepartmentRepository
{
	/**
	 * Get all departments.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(int $items = 10)
	{
		return Department::with([
			'faculties',
			'courseDepartmentFaculties.course'
		])->paginate($items);
	}

	/**
	 * Create a department.
	 *
	 * @param array $data The department data.
	 *
	 * @return \App\Department
	 */
	public function create(array $data)
	{
		return Department::create($data);
	}

	/**
	 * Update an existing department.
	 *
	 * @param \App\Department $department The department object.
	 * @param array $data The department data.
	 *
	 * @return void
	 */
	public function update(Department $department, array $data)
	{
		$department->update($data);
	}

	/**
	 * Delete an existing department.
	 *
	 * @param \App\Department $department The department object.
	 *
	 * @return void
	 */
	public function delete(Department $department)
	{
		$department->delete();
	}

	/**
	 * Attach department to faculty.
	 *
	 * @param \App\Department $department The department object.
	 * @param \App\Faculty $faculty The faculty object.
	 *
	 * @return void
	 */
	public function attach(Department $department, Faculty $faculty)
	{
		if (!$department->faculties()->find($faculty))
			$department->faculties()->attach($faculty);
	}

	/**
	 * Detach department from faculty.
	 *
	 * @param \App\Department $department The department object.
	 * @param \App\Faculty $faculty The faculty object.
	 *
	 * @return void
	 */
	public function detach(Department $department, Faculty $faculty)
	{
		$department->faculties()->detach($faculty);
	}
}