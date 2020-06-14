<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Enums\TagScope;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Repositories\Dashboard\TagRepository;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
	/**
	 * The tag repository object.
	 *
	 * @var \App\Repositories\Dashboard\TagRepository
	 */
	private $repo;

	/**
	 * Create a new TagController object.
	 *
	 * @param \App\Repositories\Dashboard\TagRepository $repo The tag repository object.
	 */
	public function __construct(TagRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all tags.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if ($request->user()->can('viewAny', Tag::class))
		{
			$items = $request->items === '*' ? '*' : intval($request->items) ?: 10;
			$tags = $this->repo->getAll($items);

			return new TagCollection($tags);
		}

		return response([], 403);
	}

	/**
	 * Store a tag.
	 *
	 * @param \App\Http\Requests\TagRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(TagRequest $request)
	{
		if ($request->user()->can('create', Tag::class))
		{
			$tag = $this->repo->create($request->only(['name']));

			return response([
				'data' => [
					'tag' => [
						'id' => $tag->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a tag.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Tag $tag)
	{
		if ($request->user()->can('view', $tag))
		{
			return response([
				'data' => [
					'tag' => new TagResource($tag)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a tag.
	 *
	 * @param \App\Http\Requests\TagRequest $request The request object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(TagRequest $request, Tag $tag)
	{
		if ($request->user()->can('update', $tag))
		{
			$this->repo->update($tag, $request->only(['name']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a tag.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Tag $tag The tag object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Tag $tag)
	{
		if ($request->user()->can('delete', $tag))
		{
			$this->repo->delete($tag);

			return response([], 204);
		}

		return response([], 403);
	}
}