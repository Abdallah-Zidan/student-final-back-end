<?php

namespace App\Repositories\Dashboard;

use App\Faculty;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class FacultyRepository
{
	/**
	 * Get all faculties.
	 *
	 * @param mixed $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($items = 10)
	{
		if ($items === '*')
		{
			$faculties = Faculty::all();

			return new LengthAwarePaginator($faculties, $faculties->count(), $faculties->count(), 1, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'page'
			]);
		}
		else
		{
			return Faculty::with([
				'university'
			])->paginate($items);
		}
	}

	/**
	 * Create a faculty.
	 *
	 * @param array $data The faculty data.
	 *
	 * @return \App\Faculty
	 */
	public function create(array $data)
	{
		return Faculty::create($data);
	}

	/**
	 * Update an existing faculty.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param array $data The faculty data.
	 *
	 * @return void
	 */
	public function update(Faculty $faculty, array $data)
	{
		$faculty->update($data);
	}

	/**
	 * Delete an existing faculty.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 *
	 * @return void
	 */
	public function delete(Faculty $faculty)
	{
		$faculty->delete();
	}
}