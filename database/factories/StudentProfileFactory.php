<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StudentProfile;
use Faker\Generator as Faker;

$factory->define(StudentProfile::class, function (Faker $faker) {
	return [
		'birthdate' => $faker->date(),
		'year' => $faker->numberBetween(1, 7),
		'user_id' => 0
	];
});