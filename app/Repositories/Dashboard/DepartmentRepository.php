<?php

namespace App\Repositories\Dashboard;

use App\Department;
use App\Faculty;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class DepartmentRepository
{
	/**
	 * Get all departments.
	 *
	 * @param mixed $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($items = 10)
	{
		if ($items === '*')
		{
			$departments = Department::all();

			return new LengthAwarePaginator($departments, $departments->count(), $departments->count(), 1, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'page'
			]);
		}
		else
			return Department::paginate($items);
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