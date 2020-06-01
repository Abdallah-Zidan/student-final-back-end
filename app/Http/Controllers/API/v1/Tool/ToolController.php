<?php

namespace App\Http\Controllers\API\v1\Tool;

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
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$user = $request->user();

		if ($user->can('viewAny', Tool::class))
		{
			$faculty_id = $user->departmentFaculties()->first()->faculty->id;
			$tools = $this->repo->getAll($faculty_id);

			return new ToolCollection($tools);
		}

		return response('', 403);
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

		if ($user->can('create', Tool::class))
		{
			$tool = $this->repo->create($user, $request->only(['title', 'body', 'type', 'files']));

			return response([
				'data' => [
					'tool' => [
						'id' => $tool->id,
						'files' => FileResource::collection($tool->files)
					]
				]
			], 201);
		}

		return response('', 403);
	}

	/**
	 * Show a tool.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param int $tool The tool object.
	 *
	 * @return void
	 */
	public function show(Request $request, Tool $tool)
	{
		if ($request->user()->can('view', $tool))
		{
			$tool->load([
				'user',
				'user.profileable',
				'faculty',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user',
				'files',
				'tags'
			]);

			return response([
				'data' => [
					'tool' => new ToolResource($tool)
				]
			]);
		}

		return response('', 403);
	}

	/**
	 * Update a tool.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 * @param int $tool The tool object.
	 *
	 * @return void
	 */
	public function update(ToolRequest $request, Tool $tool)
	{
		if ($request->user()->can('update', $tool))
		{
			$this->repo->update($tool, $request->only(['title', 'body']));

			return response('', 204);
		}

		return response('', 403);
	}

	/**
	 * Destroy a tool.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param int $tool The tool object.
	 *
	 * @return void
	 */
	public function destroy(Request $request, Tool $tool)
	{
		if ($request->user()->can('delete', $tool))
		{
			$this->repo->delete($tool);

			return response('', 204);
		}

		return response('', 403);
	}
}