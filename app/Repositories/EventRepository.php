<?php

namespace App\Repositories;

use App\DepartmentFaculty;
use App\Event;
use App\Faculty;
use App\University;
use App\User;

class EventRepository
{
	/**
	 * Get all events related to the *DepartmentFaculty* / *Faculty* / *University* group.
	 *
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($group)
	{
		
	}

	/**
	 * Create an event related to the given group and user.
	 *
	 * @param \App\User $user The user object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param array $data The event data.
	 *
	 * @return \App\Event
	 */
	public function create(User $user, $group, array $data)
	{
		
	}

	/**
	 * Update an existing event.
	 *
	 * @param \App\Event $event The event object.
	 * @param array $data The event data.
	 *
	 * @return void
	 */
	public function update(Event $event, array $data)
	{
		
	}

	/**
	 * Delete an existing event.
	 *
	 * @param \App\Event $event The event object.
	 *
	 * @return void
	 */
	public function delete(Event $event)
	{
		
	}

	/**
	 * Get all events related to the *DepartmentFaculty* group.
	 *
	 * @param \App\DepartmentFaculty $department_faculty The *DepartmentFaculty* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInDepartment(DepartmentFaculty $department_faculty)
	{
		
	}

	/**
	 * Get all events related to the *Faculty* group.
	 *
	 * @param \App\Faculty $faculty The *Faculty* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInFaculty(Faculty $faculty)
	{
		
	}

	/**
	 * Get all events related to the *University* group.
	 *
	 * @param University $university The *University* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllInUniversity(University $university)
	{
		
	}
}