<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UniversityRequest;
use App\Http\Resources\UniversityCollection;
use App\Http\Resources\UniversityResource;
use App\Repositories\Dashboard\UniversityRepository;
use App\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
	/**
	 * The university repository object.
	 *
	 * @var \App\Repositories\Dashboard\UniversityRepository
	 */
	private $repo;

	/**
	 * Create a new UniversityController object.
	 *
	 * @param \App\Repositories\Dashboard\UniversityRepository $repo The university repository object.
	 */
	public function __construct(UniversityRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all universities.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if ($request->user()->can('viewAny', University::class))
		{
			$items = $request->items === '*' ? '*' : intval($request->items) ?: 10;
			$universities = $this->repo->getAll($items);

			return new UniversityCollection($universities);
		}

		return response([], 403);
	}

	/**
	 * Store a university.
	 *
	 * @param \App\Http\Requests\UniversityRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(UniversityRequest $request)
	{
		if ($request->user()->can('create', University::class))
		{
			$university = $this->repo->create($request->only(['name']));

			return response([
				'data' => [
					'university' => [
						'id' => $university->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a university.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\University $university The university object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, University $university)
	{
		if ($request->user()->can('view', $university))
		{
			$university->load('faculties');

			return response([
				'data' => [
					'university' => new UniversityResource($university)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a university.
	 *
	 * @param \App\Http\Requests\UniversityRequest $request The request object.
	 * @param \App\University $university The university object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UniversityRequest $request, University $university)
	{
		if ($request->user()->can('update', $university))
		{
			$this->repo->update($university, $request->only(['name']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a university.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\University $university The university object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, University $university)
	{
		if ($request->user()->can('delete', $university))
		{
			$this->repo->delete($university);

			return response([], 204);
		}

		return response([], 403);
	}
}