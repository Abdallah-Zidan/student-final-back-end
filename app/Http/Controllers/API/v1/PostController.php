<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
	private $repo;

	public function __construct(PostRepository $repo)
	{
		$this->repo = $repo;
	}

	public function index(Request $request)
	{
		$user = $request->user();

		$posts = $this->repo->getPostsFor($user, $request->scope);

		return response([
			'data' => [
				'posts' => $posts
			]
		]);
	}
}