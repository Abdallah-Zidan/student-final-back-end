<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest;
use App\Http\Resources\FacultyCollection;
use App\Http\Resources\FacultyResource;
use App\Repositories\Dashboard\FacultyRepository;
use App\University;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
	/**
	 * The faculty repository object.
	 *
	 * @var \App\Repositories\Dashboard\FacultyRepository
	 */
	private $repo;

	/**
	 * Create a new FacultyController object.
	 *
	 * @param \App\Repositories\Dashboard\FacultyRepository $repo The faculty repository object.
	 */
	public function __construct(FacultyRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all faculties.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if ($request->user()->can('viewAny', Faculty::class))
		{
			$items = $request->items === '*' ? '*' : intval($request->items) ?: 10;
			$faculties = $this->repo->getAll($items);

			return new FacultyCollection($faculties);
		}

		return response([], 403);
	}

	/**
	 * Store a faculty.
	 *
	 * @param \App\Http\Requests\FacultyRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(FacultyRequest $request)
	{
		University::findOrFail($request->university_id); // Check if id exists in database, added here to throw 404 not 422 like validation

		if ($request->user()->can('create', Faculty::class))
		{
			$faculty = $this->repo->create($request->only(['name', 'university_id']));

			return response([
				'data' => [
					'faculty' => [
						'id' => $faculty->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a faculty.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Faculty $faculty The faculty object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Faculty $faculty)
	{
		if ($request->user()->can('view', $faculty))
		{
			$faculty->load([
				'university',
				'departments'
			]);

			return response([
				'data' => [
					'faculty' => new FacultyResource($faculty)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a faculty.
	 *
	 * @param \App\Http\Requests\FacultyRequest $request The request object.
	 * @param \App\Faculty $faculty The faculty object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(FacultyRequest $request, Faculty $faculty)
	{
		University::findOrFail($request->university_id);

		if ($request->user()->can('update', $faculty))
		{
			$this->repo->update($faculty, $request->only(['name', 'university_id']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a faculty.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Faculty $faculty The faculty object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Faculty $faculty)
	{
		if ($request->user()->can('delete', $faculty))
		{
			$this->repo->delete($faculty);

			return response([], 204);
		}

		return response([], 403);
	}
}