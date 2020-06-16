<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CourseDepartmentFaculty;
use App\CoursePost;
use Faker\Generator as Faker;

$factory->define(CoursePost::class, function (Faker $faker) {
    return [
		'body' => $faker->sentences(3, true),
        'user_id' => 0,
        'course_department_faculty_id'=>rand(1,CourseDepartmentFaculty::all()->count())
	];
});
