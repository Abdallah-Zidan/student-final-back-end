<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Department;
use App\Enums\UserType;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Repositories\Dashboard\DepartmentRepository;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
	/**
	 * The department repository object.
	 *
	 * @var \App\Repositories\Dashboard\DepartmentRepository
	 */
	private $repo;

	/**
	 * Create a new DepartmentController object.
	 *
	 * @param \App\Repositories\Dashboard\DepartmentRepository $repo The department repository object.
	 */
	public function __construct(DepartmentRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all departments.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if ($request->user()->can('viewAny', Department::class))
		{
			$items = $request->items === '*' ? '*' : (intval($request->items) ?: 10);
			$departments = $this->repo->getAll($items);

			return new DepartmentCollection($departments);
		}

		return response([], 403);
	}

	/**
	 * Store a department.
	 *
	 * @param \App\Http\Requests\DepartmentRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(DepartmentRequest $request)
	{
		if ($request->user()->can('create', Department::class))
		{
			$department = $this->repo->create($request->only(['name']));

			return response([
				'data' => [
					'department' => [
						'id' => $department->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a department.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Department $department The department object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Department $department)
	{
		if ($request->user()->can('view', $department))
		{
			$department->load([
				'faculties',
				'courseDepartmentFaculties.course'
			]);

			return response([
				'data' => [
					'department' => new DepartmentResource($department)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a department.
	 *
	 * @param \App\Http\Requests\DepartmentRequest $request The request object.
	 * @param \App\Department $department The department object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(DepartmentRequest $request, Department $department)
	{
		if ($request->user()->can('update', $department))
		{
			$this->repo->update($department, $request->only(['name']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a department.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Department $department The department object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Department $department)
	{
		if ($request->user()->can('delete', $department))
		{
			$this->repo->delete($department);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Attach department to faculty.
	 *
	 * @param \App\Http\Requests\DepartmentRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attach(DepartmentRequest $request)
	{
		$user = $request->user();
		$department = Department::findOrFail($request->department_id);
		$faculty = ($user->type === UserType::getTypeString(UserType::ADMIN)) ? Faculty::findOrFail($request->faculty_id) : $user->profileable->faculty;

		if ($user->can('attach', [Department::class, $faculty]))
		{
			$this->repo->attach($department, $faculty);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Detach department from faculty.
	 *
	 * @param \App\Http\Requests\DepartmentRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function detach(DepartmentRequest $request)
	{
		$user = $request->user();
		$department = Department::findOrFail($request->department_id);
		$faculty = ($user->type === UserType::getTypeString(UserType::ADMIN)) ? Faculty::findOrFail($request->faculty_id) : $user->profileable->faculty;

		if ($user->can('detach', [Department::class, $faculty]))
		{
			$this->repo->detach($department, $faculty);

			return response([], 204);
		}

		return response([], 403);
	}
}