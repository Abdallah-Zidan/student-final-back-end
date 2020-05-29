<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Http\Controllers\Controller;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileController extends Controller
{
	/**
	 * The file repository object.
	 *
	 * @var \App\Repositories\FileRepository
	 */
	private $repo;

	/**
	 * Create a new FileController object.
	 *
	 * @param \App\Repositories\FileRepository $repo The file repository object.
	 */
	public function __construct(FileRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all files.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $event)
	{
		
	}

	/**
	 * Store a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $group, int $event)
	{
		
	}

	/**
	 * Show a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $event, int $file)
	{
		
	}

	/**
	 * Update a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event, int $file)
	{
		
	}

	/**
	 * Destroy a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event, int $file)
	{
		
	}
}