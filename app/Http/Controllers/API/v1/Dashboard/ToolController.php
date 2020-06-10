<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\ToolRequest;
use App\Http\Resources\ToolCollection;
use App\Http\Resources\ToolResource;
use App\Repositories\Dashboard\ToolRepository;
use App\Tag;
use App\Tool;
use App\User;
use Illuminate\Http\Request;

class ToolController extends Controller
{
	/**
	 * The tool repository object.
	 *
	 * @var \App\Repositories\Dashboard\ToolRepository
	 */
	private $repo;

	/**
	 * Create a new ToolController object.
	 *
	 * @param \App\Repositories\Dashboard\ToolRepository $repo The tool repository object.
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

		if ($user->can('viewAny', [Tool::class, null]))
		{
			$items = intval($request->items) ?: 10;
			$tags = array_filter(array_map('trim', explode(',', $request->tags)));
			$tools = $this->repo->getAll($user, $tags, $request->type, $items);

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
		Faculty::findOrFail($request->faculty_id);
		User::findOrFail($request->user_id);

		if ($request->user()->can('create', [Tool::class, null]))
		{
			$tool = $this->repo->create($request->only(['title', 'body', 'type', 'faculty_id', 'user_id']));

			return response([
				'data' => [
					'tool' => [
						'id' => $tool->id
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
		Faculty::findOrFail($request->faculty_id);
		User::findOrFail($request->user_id);

		if ($request->user()->can('update', $tool))
		{
			$this->repo->update($tool, $request->only(['title', 'body', 'type', 'closed', 'faculty_id', 'user_id']));

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
	 * Attach tool to tag.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attach(ToolRequest $request)
	{
		$tool = Tool::findOrFail($request->tool_id);
		$tag = Tag::findOrFail($request->tag_id);

		if ($request->user()->can('attach', [Tool::class, $tool, $tag]))
		{
			$this->repo->attach($tool, $tag);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Detach tool from tag.
	 *
	 * @param \App\Http\Requests\ToolRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function detach(ToolRequest $request)
	{
		$tool = Tool::findOrFail($request->tool_id);
		$tag = Tag::findOrFail($request->tag_id);

		if ($request->user()->can('detach', [Tool::class, $tool, $tag]))
		{
			$this->repo->detach($tool, $tag);

			return response([], 204);
		}

		return response([], 403);
	}
}