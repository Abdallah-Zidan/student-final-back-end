<?php

namespace App\Repositories;

use App\User;

class DepartmentFacultyRepository
{
	/**
	 * Get all department_faculties related to user.
	 *
	 * @param \App\User $user The user object.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(User $user)
	{
		return $user->departmentFaculties()->with([
			'department',
			'faculty',
			'faculty.university',
		])->get();
	}
}