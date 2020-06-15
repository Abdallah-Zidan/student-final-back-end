<?php

namespace App\Repositories;

use App\CourseDepartmentFaculty;
use App\CoursePost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CoursePostRepository
{
    /**
     * Get all .
     *
     * @param int $type The tag scope.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(CourseDepartmentFaculty $course_department_faculty = null, $user)
    {
        if ($course_department_faculty) {

            $course_posts = $course_department_faculty->coursePosts()->orderBy('created_at','desc')->get();
        } else {
            $course_department_faculties = $user->courseDepartmentFaculties()->pluck('course_department_faculties.id');
            $course_posts = DB::table('course_posts')->whereIn('course_department_faculty_id', $course_department_faculties)->orderBy('created_at', 'desc')->get()->toArray();
            $course_posts = CoursePost::hydrate($course_posts);
            $course_posts->load(['courseDepartmentFaculty', 'courseDepartmentFaculty.course']);
        }

        $course_posts->load([
            'comments' => function ($query) {
                $query->orderBy('created_at');
            },
            'comments.user',
            'comments.replies' => function ($query) {
                $query->orderBy('created_at');
            },
            'comments.replies.user',
            'files'
        ]);
        return $course_posts;
    }

    /**
     * Create a .
     *
     * @param array $data The tag data.
     *
     * @return 
     */
    public function create($user, $data)
    {

        $course = $user->coursePosts()->create($data);
        if (array_key_exists('files', $data)) {
            foreach ($data['files'] as $file) {
                $path = Storage::disk('local')->put('files/posts/' . $course->id, $file);
                $course->files()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => Storage::mimeType($path)
                ]);
            }
        }
        return $course;
    }

    public function show($course)
    {
        return CoursePost::find($course)->with([
            'user',
            'comments' => function ($query) {
                $query->orderBy('created_at');
            },
            'comments.user',
            'comments.replies' => function ($query) {
                $query->orderBy('created_at');
            },
            'comments.replies.user',
            'files'
        ]);
    }

    public function update($course, $data)
    {
        $course->update($data);
        if (array_key_exists('files', $data)) {
            $course->files->each->delete();
            foreach ($data['files'] as $file) {
                $path = Storage::disk('local')->put('files/posts/' . $course->id, $file);
                $course->files()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => Storage::mimeType($path)
                ]);
            }
        }
    }

    public function delete($course)
    {
        $course->delete();
    }
}
