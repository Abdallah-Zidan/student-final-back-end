<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Enums\PostScope;
use App\Http\Controllers\Controller;

class ScopeController extends Controller
{
	public function index()
	{
		return response([
			'data' => [
				'scopes' => PostScope::getAllScopes()
			]
		]);
	}
}