<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Event;
use App\File;
use App\Http\Controllers\Controller;
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
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $event)
	{
		if ($group)
			$event = $group->events()->findOrFail($event);
		else
			$event = Event::findOrFail($event);

		if ($request->user()->can('viewAny', [File::class, $event]))
		{
			$files = $this->repo->getAll($event);

			return new FileCollection($files);
		}

		return response('', 403);
	}

	/**
	 * Store a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $group, int $event)
	{
		if ($group)
			$event = $group->events()->findOrFail($event);
		else
			$event = Event::findOrFail($event);

		if ($request->user()->can('create', [File::class, $event]))
		{
			$file = $this->repo->create($event, 'events', $request->only(['file']));

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
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 * @param int $file The file id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $event, int $file)
	{
		if ($group)
			$file = $group->events()->findOrFail($event)->files()->findOrFail($file);
		else
			$file = Event::findOrFail($event)->files()->findOrFail($file);

		if ($request->user()->can('view', [$file, $event]))
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
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event, int $file)
	{
		if ($group)
			$file = $group->events()->findOrFail($event)->files()->findOrFail($file);
		else
			$file = Event::findOrFail($event)->files()->findOrFail($file);

		if ($request->user()->can('update', [$file, $event]))
		{
			$this->repo->update($file, 'events/' . $event->id, $request->only(['file']));

			return response('', 204);
		}

		return response('', 403);
	}

	/**
	 * Destroy a file.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *Faculty* / *University* / *All (null)* object.
	 * @param int $event The event id.
	 * @param int $file The file id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event, int $file)
	{
		if ($group)
			$file = $group->events()->findOrFail($event)->files()->findOrFail($file);
		else
			$file = Event::findOrFail($event)->files()->findOrFail($file);

		if ($request->user()->can('delete', [$file, $event]))
		{
			$this->repo->delete($file);

			return response('', 204);
		}

		return response('', 403);
	}
}