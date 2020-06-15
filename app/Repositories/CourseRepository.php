<?php

namespace App\Repositories;

use App\Http\Resources\CourseDepartmentFacultycollection;
use App\User;

class CourseRepository
{
    /**
     * Get all courses.
     *
     * 
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(User $user)
    {
        return $user->courseDepartmentFaculties->load('course');;
    }

    
}
