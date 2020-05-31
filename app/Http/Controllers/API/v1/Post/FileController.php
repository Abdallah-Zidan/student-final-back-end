<?php

namespace App\Http\Controllers\API\v1\Post;

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
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $post)
	{
		$post = $group->posts()->findOrFail($post);

		if ($request->user()->can('viewAny', [File::class, $post]))
		{
			$files = $this->repo->getAll($post);

			return new FileCollection($files);
		}

		return response('', 403);
	}

	/**
	 * Store a file.
	 *
	 * @param \App\Http\Requests\FileRequest $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(FileRequest $request, $group, int $post)
	{
		$post = $group->posts()->findOrFail($post);

		if ($request->user()->can('create', [File::class, $post]))
		{
			$file = $this->repo->create($post, 'posts', $request->only(['file']));

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
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $post, int $file)
	{
		$file = $group->posts()->findOrFail($post)->files()->findOrFail($file);

		if ($request->user()->can('view', [$file, $post]))
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
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function update(FileRequest $request, $group, int $post, int $file)
	{
		$file = $group->posts()->findOrFail($post)->files()->findOrFail($file);

		if ($request->user()->can('update', [$file, $post]))
		{
			$this->repo->update($file, 'posts/' . $post->id, $request->only(['file']));

			return response('', 204);
		}

		return response('', 403);
	}

	/**
	 * Destroy a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $post, int $file)
	{
		$file = $group->posts()->findOrFail($post)->files()->findOrFail($file);

		if ($request->user()->can('delete', [$file, $post]))
		{
			$this->repo->delete($file);

			return response('', 204);
		}

		return response('', 403);
	}
}