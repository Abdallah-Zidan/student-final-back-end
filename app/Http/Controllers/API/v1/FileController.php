<?php

namespace App\Http\Controllers\API\v1;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileCollection;
use App\Http\Resources\FileResource;
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
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $parent)
	{
		if ($request->user()->can('viewAny', [File::class, $parent]))
		{
			$files = $this->repo->getAll($parent);

			return new FileCollection($files);
		}

		return response([], 403);
	}

	/**
	 * Store a file.
	 *
	 * @param \App\Http\Requests\FileRequest $request The request object.
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(FileRequest $request, $parent)
	{
		if ($request->user()->can('create', [File::class, $parent]))
		{
			$file = $this->repo->create($parent, $request->only(['file']));

			return response([
				'data' => [
					'file' => new FileResource($file)
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $parent, int $file)
	{
		$file = $parent->files()->findOrFail($file);

		if ($request->user()->can('view', $file))
		{
			return response([
				'data' => [
					'file' => new FileResource($file)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a file.
	 *
	 * @param \App\Http\Requests\FileRequest $request The request object.
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(FileRequest $request, $parent, int $file)
	{
		$file = $parent->files()->findOrFail($file);

		if ($request->user()->can('update', $file))
		{
			$this->repo->update($file, $request->only(['file']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $parent, int $file)
	{
		$file = $parent->files()->findOrFail($file);

		if ($request->user()->can('delete', $file))
		{
			$this->repo->delete($file);

			return response([], 204);
		}

		return response([], 403);
	}
}