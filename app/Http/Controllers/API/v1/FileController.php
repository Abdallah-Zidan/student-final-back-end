<?php

namespace App\Http\Controllers\API\v1;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileRequest;
use App\Http\Resources\FileResource;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileController extends Controller
{
	private $repo;

	public function __construct(FileRepository $repo)
	{
		$this->repo = $repo;
	}

	public function store(StoreFileRequest $request)
	{
		$file = $this->repo->create($request->user(), $request->only(['resource', 'resource_id', 'file']));

		if ($file === false)
			return response('', 401);

		return response([
			'data' => [
				'file' => new FileResource($file)
			]
		], 201);
	}

	public function destroy(Request $request, File $file)
	{
		$deleted = $this->repo->delete($request->user(), $file);

		if (!$deleted)
			return response('', 401);

		return response('', 204);
	}
}