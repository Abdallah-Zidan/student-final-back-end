<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Http\Controllers\Controller;
use App\Repositories\ReplyRepository;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	/**
	 * The reply repository object.
	 *
	 * @var \App\Repositories\ReplyRepository
	 */
	private $repo;

	/**
	 * Create a new ReplyController object.
	 *
	 * @param \App\Repositories\ReplyRepository $repo The reply repository object.
	 */
	public function __construct(ReplyRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all replies.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $group, int $event, int $comment)
	{
		
	}

	/**
	 * Store a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $group, int $event, int $comment)
	{
		
	}

	/**
	 * Show a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $group, int $event, int $comment, int $reply)
	{
		
	}

	/**
	 * Update a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return void
	 */
	public function update(Request $request, $group, int $event, int $comment, int $reply)
	{
		
	}

	/**
	 * Destroy a reply.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param @param mixed $group The *DepartmentFaculty* / *Faculty* / *University* object.
	 * @param int $event The event id.
	 * @param int $comment The comment id.
	 * @param int $reply The reply id.
	 *
	 * @return void
	 */
	public function destroy(Request $request, $group, int $event, int $comment, int $reply)
	{
		
	}
}