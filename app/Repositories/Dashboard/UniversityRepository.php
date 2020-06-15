<?php

namespace App\Repositories\Dashboard;

use App\University;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class UniversityRepository
{
	/**
	 * Get all universities.
	 *
	 * @param mixed $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($items = 10)
	{
		if ($items === '*')
		{
			$universities = University::all();

			return new LengthAwarePaginator($universities, $universities->count(), $universities->count(), 1, [
				'path' => Paginator::resolveCurrentPath(),
				'pageName' => 'page'
			]);
		}
		else
			return University::paginate($items);
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