<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CourseDepartmentFaculty;
use Faker\Generator as Faker;

$factory->define(CourseDepartmentFaculty::class, function (Faker $faker) {
	return [
		'course_id' => 0,
		'department_faculty_id' => 0
	];
});