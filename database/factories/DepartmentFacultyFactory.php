<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DepartmentFaculty;
use Faker\Generator as Faker;

$factory->define(DepartmentFaculty::class, function (Faker $faker) {
	return [
		'department_id' => 0,
		'faculty_id' => 0
	];
});