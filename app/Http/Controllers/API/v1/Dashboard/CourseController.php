<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Course;
use App\DepartmentFaculty;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Repositories\Dashboard\CourseRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
	/**
	 * The course repository object.
	 *
	 * @var \App\Repositories\Dashboard\CourseRepository
	 */
	private $repo;

	/**
	 * Create a new CourseController object.
	 *
	 * @param \App\Repositories\Dashboard\CourseRepository $repo The course repository object.
	 */
	public function __construct(CourseRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * Get all courses.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if ($request->user()->can('viewAny', Course::class))
		{
			$items = $request->items === '*' ? '*' : intval($request->items) ?: 10;
			$courses = $this->repo->getAll($items);

			return new CourseCollection($courses);
		}

		return response([], 403);
	}

	/**
	 * Store a course.
	 *
	 * @param \App\Http\Requests\CourseRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(CourseRequest $request)
	{
		if ($request->user()->can('create', Course::class))
		{
			$course = $this->repo->create($request->only(['name', 'description']));

			return response([
				'data' => [
					'course' => [
						'id' => $course->id
					]
				]
			], 201);
		}

		return response([], 403);
	}

	/**
	 * Show a course.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Course $course The course object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, Course $course)
	{
		if ($request->user()->can('view', $course))
		{
			$course->load([
				'departmentFaculties.faculty',
				'departmentFaculties.department'
			]);

			return response([
				'data' => [
					'course' => new CourseResource($course)
				]
			]);
		}

		return response([], 403);
	}

	/**
	 * Update a course.
	 *
	 * @param \App\Http\Requests\CourseRequest $request The request object.
	 * @param \App\Course $course The course object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(CourseRequest $request, Course $course)
	{
		if ($request->user()->can('update', $course))
		{
			$this->repo->update($course, $request->only(['name', 'description']));

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Destroy a course.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @param \App\Course $course The course object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Course $course)
	{
		if ($request->user()->can('delete', $course))
		{
			$this->repo->delete($course);

			return response([], 204);
		}

		return response([], 403);
	}

	/**
	 * Attach course to department_faculty.
	 *
	 * @param \App\Http\Requests\CourseRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attach(CourseRequest $request)
	{
		$user = $request->user();
		$course = Course::findOrFail($request->course_id);
		$department_faculty = DepartmentFaculty::where([
			['department_id', $request->department_id],
			['faculty_id', ($user->type === UserType::getTypeString(UserType::ADMIN)) ? $request->faculty_id : $user->profileable->faculty->id]
		])->firstOrFail();

		if ($user->can('attach', [Course::class, $department_faculty]))
		{
			$this->repo->attach($course, $department_faculty);

			return response([], 201);
		}

		return response([], 403);
	}

	/**
	 * Detach course from department_faculty.
	 *
	 * @param \App\Http\Requests\CourseRequest $request The request object.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function detach(CourseRequest $request)
	{
		$user = $request->user();
		$course = Course::findOrFail($request->course_id);
		$department_faculty = DepartmentFaculty::where([
			['department_id', $request->department_id],
			['faculty_id', ($user->type === UserType::getTypeString(UserType::ADMIN)) ? $request->faculty_id : $user->profileable->faculty->id]
		])->firstOrFail();

		if ($user->can('detach', [Course::class, $department_faculty]))
		{
			$this->repo->detach($course, $department_faculty);

			return response([], 204);
		}

		return response([], 403);
	}
}