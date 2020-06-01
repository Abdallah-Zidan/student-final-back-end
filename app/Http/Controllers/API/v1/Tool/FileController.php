<?php

namespace App\Http\Controllers\API\v1\Tool;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileCollection;
use App\Http\Resources\FileResource;
use App\Repositories\FileRepository;
use App\Tool;
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
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, Tool $tool)
	{
		if ($request->user()->can('viewAny', [File::class, $tool]))
		{
			$files = $this->repo->getAll($tool);

			return new FileCollection($files);
		}

		return response('', 403);
	}

	/**
	 * Store a file.
	 *
	 * @param \App\Http\Requests\FileRequest $request The request object.
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(FileRequest $request, Tool $tool)
	{
		if ($request->user()->can('create', [File::class, $tool]))
		{
			$file = $this->repo->create($tool, 'tools', $request->only(['file']));

			return response([
				'data' => [
					'file' => new FileResource($file)
				]
			], 201);
		}

		return response('', 403);
	}

	/**
	 * Show a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Tool $tool, int $file)
	{
		$file = $tool->files()->findOrFail($file);

		if ($request->user()->can('view', [$file, $tool]))
		{
			return response([
				'data' => [
					'file' => new FileResource($file)
				]
			]);
		}

		return response('', 403);
	}

	/**
	 * Update a file.
	 *
	 * @param \App\Http\Requests\FileRequest $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function update(FileRequest $request, Tool $tool, int $file)
	{
		$file = $tool->files()->findOrFail($file);

		if ($request->user()->can('update', [$file, $tool]))
		{
			$this->repo->update($file, 'tools/' . $tool->id, $request->only(['file']));

			return response('', 204);
		}

		return response('', 403);
	}

	/**
	 * Destroy a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, Tool $tool, int $file)
	{
		$file = $tool->files()->findOrFail($file);

		if ($request->user()->can('delete', [$file, $tool]))
		{
			$this->repo->delete($file);

			return response('', 204);
		}

		return response('', 403);
	}
}