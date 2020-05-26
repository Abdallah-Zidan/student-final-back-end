<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexPostRequest;
use App\Http\Resources\PostResource;
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

		$posts = $this->repo->getPostsFor($user, $request->department_faculty_id, $request->scope);

		return response([
			'data' => [
				'posts' => PostResource::collection($posts)
			]
		]);
	}
}