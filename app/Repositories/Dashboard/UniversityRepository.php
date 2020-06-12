<?php

namespace App\Repositories\Dashboard;

use App\University;

class UniversityRepository
{
	/**
	 * Get all universities.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(int $items = 10)
	{
		return University::with('faculties')->paginate($items);
	}

	/**
	 * Create a university.
	 *
	 * @param array $data The university data.
	 *
	 * @return \App\University
	 */
	public function create(array $data)
	{
		return University::create($data);
	}

	/**
	 * Update an existing university.
	 *
	 * @param \App\University $university The university object.
	 * @param array $data The university data.
	 *
	 * @return void
	 */
	public function update(University $university, array $data)
	{
		$university->update($data);
	}

	/**
	 * Delete an existing university.
	 *
	 * @param \App\University $university The university object.
	 *
	 * @return void
	 */
	public function delete(University $university)
	{
		$university->delete();
	}
}