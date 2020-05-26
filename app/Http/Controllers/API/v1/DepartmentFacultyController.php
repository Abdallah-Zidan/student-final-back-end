<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\DepartmentFacultyRepository;
use Illuminate\Http\Request;

class DepartmentFacultyController extends Controller
{
	private $repo;

	public function __construct(DepartmentFacultyRepository $repo)
	{
		$this->repo = $repo;
	}

	public function index(Request $request)
	{
		$user = $request->user();

		$department_faculties = $this->repo->getDepartmentFacultiesFor($user);

		return response([
			'data' => [
				'department_faculties' => $department_faculties
			]
		]);
	}
}