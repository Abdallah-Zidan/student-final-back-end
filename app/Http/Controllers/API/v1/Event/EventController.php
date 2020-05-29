<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
	/**
	 * The event repository object.
	 *
	 * @var \App\Repositories\EventRepository
	 */
	private $repo;

	/**
	 * Create a new EventController object.
	 *
	 * @param \App\Repositories\EventRepository $repo The event repository object.
	 */
	public function __construct(EventRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all events.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group)
	{
		
	}

	/**
	 * Store an event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $group)
	{
		
	}

	/**
	 * Show an event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return void
	 */
	public function show(Request $request, $group, int $event)
	{
		
	}

	/**
	 * Update an event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event)
	{
		
	}

	/**
	 * Destroy an event.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event)
	{
		
	}
}