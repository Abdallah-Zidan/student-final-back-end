<?php

namespace App\Repositories\Dashboard;

use App\Faculty;

class FacultyRepository
{
	/**
	 * Get all faculties.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(int $items = 10)
	{
		return Faculty::with([
			'university',
			'departments'
		])->paginate($items);
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