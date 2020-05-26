<?php

namespace App\Repositories;

use App\University;

class UniversityRepository
{
	public function getUniversities()
	{
		return University::with([
			'faculties',
			'faculties.departments'
		])->get();
	}
}