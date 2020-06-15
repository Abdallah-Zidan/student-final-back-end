<?php

namespace App\Http\Controllers\API\v1\User;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseDepartmentFacultycollection;
use App\Repositories\CourseRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $repo;
    
    public function __construct(CourseRepository $repo)
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
        $courses = $this->repo->getAll($request->user());
        return new CourseDepartmentFacultycollection($courses);
    }

    
}
