<?php

namespace App\Http\Controllers\API\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentFacultyResource;
use App\Repositories\DepartmentFacultyRepository;
use Illuminate\Http\Request;

class DepartmentFacultyController extends Controller
{
	/**
	 * The department_faculty repository object.
	 *
	 * @var \App\Repositories\DepartmentFacultyRepository
	 */
	private $repo;

	/**
	 * Create a new DepartmentFacultyController object.
	 *
	 * @param \App\Repositories\DepartmentFacultyRepository $repo The department_faculty repository object.
	 */
	public function __construct(DepartmentFacultyRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all departments of user.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$department_faculties = $this->repo->getAll($request->user());

		return response([
			'data' => [
				'department_faculties' => DepartmentFacultyResource::collection($department_faculties)
			]
		]);
	}
}