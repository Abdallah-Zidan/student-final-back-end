<?php

namespace App\Repositories;

use App\University;

class UniversityRepository
{
	/**
	 * Get all universities.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll()
	{
		return University::with([
			'faculties',
			'faculties.departments'
		])->get();
	}
}