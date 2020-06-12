<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\CourseDepartmentFaculty;
use App\DepartmentFaculty;
use App\Enums\UserType;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Repositories\Dashboard\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	/**
	 * The user repository object.
	 *
	 * @var \App\Repositories\Dashboard\UserRepository
	 */
	private $repo;

	/**
	 * Create a new UserController object.
	 *
	 * @param \App\Repositories\Dashboard\UserRepository $repo The user repository object.
	 */
	public function __construct(UserRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all users.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$user = $request->user();

		if ($user->can('viewAny', User::class))
		{
			$items = intval($request->items) ?: 10;
			$users = $this->repo->getAll($user, $items);

			return new UserCollection($users);
		}

		return response([], 403);
	}

	/**
	 * Store a user.
	 *
	 * @param \App\Http\Requests\UserRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserRequest $request)
	{
		$user = $request->user();

		if ($user->type === UserType::getTypeString(UserType::ADMIN) && $request->has('faculty_id'))
			Faculty::findOrFail($request->faculty_id);

		if ($user->can('create', [User::class, $request->type]))
		{
			$data = $request->only(['name', 'email', 'password', 'gender', 'address', 'mobile', 'avatar', 'type']);
			$profile_data = $request->only(['birthdate', 'year', 'scientific_certificates', 'fax', 'description', 'website']) + ($user->type === UserType::getTypeString(UserType::ADMIN) ? [
				'faculty_id' => $request->faculty_id
			] : [
				'faculty_id' => $user->profileable->faculty->id
			]);
			$user = $this->repo->create($data, $profile_data);

			return response([
				'data' => [
					'user' => [
						'id' => $user->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a user.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\User $user The user object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, User $user)
	{
		if ($request->user()->can('view', $user))
		{
			if ($user->type === UserType::getTypeString(UserType::STUDENT) ||
				$user->type === UserType::getTypeString(UserType::TEACHING_STAFF) ||
				$user->type === UserType::getTypeString(UserType::COMPANY))
				$user->load('profileable');

			if ($user->type === UserType::getTypeString(UserType::MODERATOR))
				$user->load('profileable.faculty.university');

			return response([
				'data' => [
					'user' => new UserResource($user)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a user.
	 *
	 * @param \App\Http\Requests\UserRequest $request The request object.
	 * @param \App\User $user The user object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserRequest $request, User $user)
	{
		$current_user = $request->user();

		if ($current_user->type === UserType::getTypeString(UserType::ADMIN) && $request->has('faculty_id'))
			Faculty::findOrFail($request->faculty_id);

		if ($current_user->can('update', $user))
		{
			$data = $request->only(['name', 'email', 'password', 'gender', 'blocked', 'address', 'mobile', 'avatar']);
			$profile_data = $request->only(['birthdate', 'year', 'scientific_certificates', 'fax', 'description', 'website']) + ($user->type === UserType::getTypeString(UserType::ADMIN) ? $request->only(['faculty_id']) : []);
			$this->repo->update($user, $data, $profile_data);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a user.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\User $user The user object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, User $user)
	{
		if ($request->user()->can('delete', $user))
		{
			$this->repo->delete($user);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Attach department to user.
	 *
	 * @param \App\Http\Requests\UserRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attachDepartment(UserRequest $request)
	{
		$user = User::findOrFail($request->user_id);
		$department_faculty = DepartmentFaculty::where([
			['department_id', $request->department_id],
			['faculty_id', ($user->type === UserType::getTypeString(UserType::ADMIN)) ? $request->faculty_id : $user->profileable->faculty->id]
		])->firstOrFail();

		if ($request->user()->can('attachDepartment', [User::class, $user, $department_faculty]))
		{
			$this->repo->attachDepartment($user, $department_faculty);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Detach department from user.
	 *
	 * @param \App\Http\Requests\UserRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function detachDepartment(UserRequest $request)
	{
		$user = User::findOrFail($request->user_id);
		$department_faculty = DepartmentFaculty::where([
			['department_id', $request->department_id],
			['faculty_id', ($user->type === UserType::getTypeString(UserType::ADMIN)) ? $request->faculty_id : $user->profileable->faculty->id]
		])->firstOrFail();

		if ($request->user()->can('detachDepartment', [User::class, $user, $department_faculty]))
		{
			$this->repo->detachDepartment($user, $department_faculty);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Attach course to user.
	 *
	 * @param \App\Http\Requests\UserRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attachCourse(UserRequest $request)
	{
		$user = User::findOrFail($request->user_id);
		$department_faculty = DepartmentFaculty::where([
			['department_id', $request->department_id],
			['faculty_id', ($user->type === UserType::getTypeString(UserType::ADMIN)) ? $request->faculty_id : $user->profileable->faculty->id]
		])->firstOrFail();
		$course_department_faculty = CourseDepartmentFaculty::where([
			['course_id', $request->course_id],
			['department_faculty_id', $department_faculty->id]
		])->firstOrFail();

		if ($request->user()->can('attachCourse', [User::class, $user, $course_department_faculty]))
		{
			$this->repo->attachCourse($user, $course_department_faculty);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Detach course from user.
	 *
	 * @param \App\Http\Requests\UserRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function detachCourse(UserRequest $request)
	{
		$user = User::findOrFail($request->user_id);
		$department_faculty = DepartmentFaculty::where([
			['department_id', $request->department_id],
			['faculty_id', ($user->type === UserType::getTypeString(UserType::ADMIN)) ? $request->faculty_id : $user->profileable->faculty->id]
		])->firstOrFail();
		$course_department_faculty = CourseDepartmentFaculty::where([
			['course_id', $request->course_id],
			['department_faculty_id', $department_faculty->id]
		])->firstOrFail();

		if ($request->user()->can('detachCourse', [User::class, $user, $course_department_faculty]))
		{
			$this->repo->detachCourse($user, $course_department_faculty);

			return response([], 204);
		}

		return response([], 403);
	}
}