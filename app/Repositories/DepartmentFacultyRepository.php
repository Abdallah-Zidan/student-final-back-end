<?php

namespace App\Repositories;

use App\User;

class DepartmentFacultyRepository
{
	public function getDepartmentFacultiesFor(User $current_user)
	{
		return $current_user->departmentFaculties()->with([
			'department',
			'faculty',
			'faculty.university',
		])->get();
	}
}