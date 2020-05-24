<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
	return [
		'body' => $faker->sentences(3, true),
		'reported' => $faker->boolean,
		'user_id' => 0,
		'department_faculty_id' => 0
	];
});