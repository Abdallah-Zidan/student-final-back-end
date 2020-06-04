<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UniversityCollection;
use App\Repositories\UniversityRepository;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
	/**
	 * The university repository object.
	 *
	 * @var \App\Repositories\UniversityRepository
	 */
	private $repo;

	/**
	 * Create a new UniversityController object.
	 *
	 * @param \App\Repositories\UniversityRepository $repo The university repository object.
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
		$universities = $this->repo->getAll();

		return response([
			'data' => [
				'universities' => UniversityCollection::collection($universities)
			]
		]);
	}
}