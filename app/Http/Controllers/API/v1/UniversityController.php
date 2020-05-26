<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UniversityCollection;
use App\Repositories\UniversityRepository;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
	private $repo;

	public function __construct(UniversityRepository $repo)
	{
		$this->repo = $repo;
	}

	public function index()
	{
		$universities = $this->repo->getUniversities();

		return response([
			'data' => [
				'universities' => UniversityCollection::collection($universities)
			]
		]);
	}
}