<?php

namespace App\Http\Controllers\API\v1;

use App\CoursePost;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoursePostRequest;
use App\Http\Resources\CoursePostCollection;
use App\Http\Resources\FileResource;
use App\Repositories\CoursePostRepository;
use Illuminate\Http\Request;

class CoursePostController extends Controller
{
    private $repo;
    public function __construct(CoursePostRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($course_department_faculty = $request->course)
        {
            $course_department_faculty = $request->user()->courseDepartmentFaculties()->findOrFail($course_department_faculty);
        }
        $course_posts = $this->repo->getAll($course_department_faculty, $request->user());
        return new CoursePostCollection($course_posts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CoursePostRequest $request)
    {
        $course = $this->repo->create(
            $request->user(),
            $request->only([
                'body',
                'course_department_faculty_id',
                'files'
            ])
        );
        return response([
            'data' => [
                'coursePost' => [
                    'id' => $course->id,
                    'files' => FileResource::collection($course->files)
                ]
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CoursePost  $coursePost
     * @return \Illuminate\Http\Response
     */
    public function show($coursePost)
    {
        $coursePost = $this->repo->show($coursePost);
        return new CoursePost($coursePost);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CoursePost  $coursePost
     * @return \Illuminate\Http\Response
     */
    public function update(CoursePostRequest $request, CoursePost $course)
    {
        $this->repo->update($course,$request->only(['body','files']));
        return response([],204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CoursePost  $coursePost
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoursePost $course)
    {
        $this->repo->delete($course);
        return response([],204);

    }
}
