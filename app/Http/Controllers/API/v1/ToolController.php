<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToolRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\ToolCollection;
use App\Http\Resources\ToolResource;
use App\Repositories\ToolRepository;
use App\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
	/**
	 * The tool repository object.
	 *
	 * @var \App\Repositories\ToolRepository
	 */
	private $repo;

	/**
	 * Create a new ToolController object.
	 *
	 * @param \App\Repositories\ToolRepository $repo The tool repository object.
	 */
	public function __construct(ToolRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all tools.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(ToolRequest $request)
	{
		$user = $request->user();
		$faculty = $user->departmentFaculties()->first()->faculty;

		if ($user->can('viewAny', [Tool::class, $faculty]))
		{
			$tags = array_filter(array_map('trim', explode(',', $request->tags)));
			$tools = $this->repo->getAll($faculty, $request->type, $tags);

			return new ToolCollection($tools);
		}

		return response([], 403);
	}

	/**
	 * Store a tool.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(ToolRequest $request)
	{
		$user = $request->user();
		$faculty = $user->departmentFaculties()->first()->faculty;

		if ($user->can('create', [Tool::class, $faculty]))
		{
			$tags = array_filter(array_map('trim', $request->tags));
			$tool = $this->repo->create($user, $faculty, array_merge($request->only(['title', 'body', 'type', 'files']), ['tags' => $tags]));

			return response([
				'data' => [
					'tool' => [
						'id' => $tool->id,
						'files' => FileResource::collection($tool->files)
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a tool.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Tool $tool)
	{
		if ($request->user()->can('view', $tool))
		{
			$tool->load([
				'user',
				'faculty',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'files',
				'tags'
			]);

			return response([
				'data' => [
					'tool' => new ToolResource($tool)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a tool.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(ToolRequest $request, Tool $tool)
	{
		if ($request->user()->can('update', $tool))
		{
			$tags = array_filter(array_map('trim', $request->tags));
			$this->repo->update($tool, array_merge($request->only(['title', 'body']), ['tags' => $tags]));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a tool.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tool $tool The tool object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Tool $tool)
	{
		if ($request->user()->can('delete', $tool))
		{
			$this->repo->delete($tool);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Close a tool request.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function close(ToolRequest $request)
	{
		$tool = Tool::findOrFail($request->id);

		if ($request->user()->can('close', $tool))
		{
			$this->repo->close($tool);

			return response([], 204);
		}

		return response([], 403);
	}
}