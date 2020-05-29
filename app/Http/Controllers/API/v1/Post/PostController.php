<?php

namespace App\Http\Controllers\API\v1\Post;

use App\DepartmentFaculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
	/**
	 * The post repository object.
	 *
	 * @var \App\Repositories\PostRepository
	 */
	private $repo;

	/**
	 * Create a new PostController object.
	 *
	 * @param \App\Repositories\PostRepository $repo The post repository object.
	 */
	public function __construct(PostRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all posts.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group)
	{
		if ($request->user()->can('viewAny', [Post::class, $group]))
		{
			$posts = $this->repo->getAll($group);

			return new PostCollection($posts);
		}

		return response('', 403);
	}

	/**
	 * Store a post.
	 *
	 * @param \App\Http\Requests\PostRequest $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(PostRequest $request, $group)
	{
		$user = $request->user();

		if ($user->can('create', [Post::class, $group]))
		{
			$post = $this->repo->create($user, $group, $request->only(['body', 'files']));

			return response([
				'data' => [
					'post' => [
						'id' => $post->id,
						'files' => FileResource::collection($post->files)
					]
				]
			], 201);
		}

		return response('', 403);
	}

	/**
	 * Show a post.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 *
	 * @return void
	 */
	public function show(Request $request, $group, int $post)
	{
		$post = $group->posts()->findOrFail($post);

		if ($request->user()->can('view', [$post, $group]))
		{
			$post->load([
				'user',
				'user.profileable',
				'scopeable',
				'comments' => function ($query) { $query->orderBy('created_at'); },
				'comments.user',
				'comments.replies' => function ($query) { $query->orderBy('created_at'); },
				'comments.replies.user',
				'files'
			]);

			if ($group instanceof DepartmentFaculty)
			{
				$post->load([
					'scopeable.department',
					'scopeable.faculty',
					'scopeable.faculty.university'
				]);
			}

			return response([
				'data' => [
					'post' => new PostResource($post)
				]
			]);
		}

		return response('', 403);
	}

	/**
	 * Update a post.
	 *
	 * @param \App\Http\Requests\PostRequest $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 *
	 * @return void
	 */
	public function update(PostRequest $request, $group, int $post)
	{
		$post = $group->posts()->findOrFail($post);

		if ($request->user()->can('update', [$post, $group]))
		{
			$this->repo->update($post, $request->only(['body']));

			return response('', 204);
		}

		return response('', 403);
	}

	/**
	 * Destroy a post.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $post The post id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $post)
	{
		$post = $group->posts()->findOrFail($post);

		if ($request->user()->can('delete', [$post, $group]))
		{
			$this->repo->delete($post);

			return response('', 204);
		}

		return response('', 403);
	}
}