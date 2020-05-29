<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Http\Controllers\Controller;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	/**
	 * The comment repository object.
	 *
	 * @var \App\Repositories\CommentRepository
	 */
	private $repo;

	/**
	 * Create a new CommentController object.
	 *
	 * @param \App\Repositories\CommentRepository $repo The comment repository object.
	 */
	public function __construct(CommentRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all comments.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $event)
	{
		
	}

	/**
	 * Store a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $group, int $event)
	{
		
	}

	/**
	 * Show a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $event, int $comment)
	{
		
	}

	/**
	 * Update a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event, int $comment)
	{
		
	}

	/**
	 * Destroy a comment.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event, int $comment)
	{
		
	}
}