<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\PostCollection;
use App\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
	private $repo;

	public function __construct(PostRepository $repo)
	{
		$this->repo = $repo;
	}

	public function index(IndexPostRequest $request)
	{
		$user = $request->user();

		$posts = $this->repo->getPostsFor($user, $request->scope, $request->scope_id);

		if ($posts === false)
			return response('', 401);

		return new PostCollection($posts);;
	}

	public function store(StorePostRequest $request)
	{
		$post = $this->repo->create($request->user(), $request->only(['body', 'scope', 'scope_id', 'files']));

		if ($post === false)
			return response('', 401);

		return response([
			'data' => [
				'post' => [
					'id' => $post->id,
					'files' => FileResource::collection($post->files)
				]
			]
		], 201);
	}

	public function update(UpdatePostRequest $request, Post $post)
	{
		$updated = $this->repo->update($request->user(), $post, $request->only(['body']));

		if (!$updated)
			return response('', 401);

		return response('', 204);
	}

	public function destroy(Request $request, Post $post)
	{
		$deleted = $this->repo->delete($request->user(), $post);

		if (!$deleted)
			return response('', 401);

		return response('', 204);
	}
}