<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Enums\PostScope;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Post;
use App\Repositories\Dashboard\PostRepository;
use App\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
	/**
	 * The post repository object.
	 *
	 * @var \App\Repositories\Dashboard\PostRepository
	 */
	private $repo;

	/**
	 * Create a new PostController object.
	 *
	 * @param \App\Repositories\Dashboard\PostRepository $repo The post repository object.
	 */
	public function __construct(PostRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all posts.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$user = $request->user();

		if ($user->can('viewAny', [Post::class, null]))
		{
			$items = intval($request->items) ?: 10;
			$posts = $this->repo->getAll($user, $items);

			return new PostCollection($posts);
		}

		return response([], 403);
	}

	/**
	 * Store a post.
	 *
	 * @param \App\Http\Requests\PostRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(PostRequest $request)
	{
		$user = $request->user();
		$model = PostScope::getScopeModel($request->group);
		$group = $model::findOrFail($request->group_id);

		if ($user->type === UserType::getTypeString(UserType::ADMIN))
			User::findOrFail($request->user_id);

		if ($user->can('create', [Post::class, $group]))
		{
			$data = $request->only(['body', 'year']) + [
				'scopeable_type' => get_class($group),
				'scopeable_id' => $group->id
			] + ($user->type === UserType::getTypeString(UserType::ADMIN) ? [
				'user_id' => $request->user_id
			] : [
				'user_id' => $user->id
			]);
			$post = $this->repo->create($data);

			return response([
				'data' => [
					'post' => [
						'id' => $post->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a post.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Post $post The post object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Post $post)
	{
		if ($request->user()->can('view', $post))
		{
			$post->load([
				'user',
				'scopeable'
			]);

			if ($post->scope === PostScope::getScopeString(PostScope::DEPARTMENT))
			{
				$post->load([
					'scopeable.department',
					'scopeable.faculty.university'
				]);
			}

			return response([
				'data' => [
					'post' => new PostResource($post)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a post.
	 *
	 * @param \App\Http\Requests\PostRequest $request The request object.
	 * @param \App\Post $post The post object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(PostRequest $request, Post $post)
	{
		$user = $request->user();
		$model = PostScope::getScopeModel($request->group);
		$group = $model::findOrFail($request->group_id);

		if ($user->type === UserType::getTypeString(UserType::ADMIN))
			User::findOrFail($request->user_id);

		if ($user->can('update', $post))
		{
			$data = $request->only(['body', 'reported', 'year']) + [
				'scopeable_type' => get_class($group),
				'scopeable_id' => $group->id
			] + ($user->type === UserType::getTypeString(UserType::ADMIN) ? $request->only(['user_id']) : []);
			$this->repo->update($post, $data);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a post.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Post $post The post object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Post $post)
	{
		if ($request->user()->can('delete', $post))
		{
			$this->repo->delete($post);

			return response([], 204);
		}

		return response([], 403);
	}
}