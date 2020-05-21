<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TeachingStaffProfile;
use Faker\Generator as Faker;

$factory->define(TeachingStaffProfile::class, function (Faker $faker) {
	return [
		'birthdate' => $faker->date(),
		'scientific_certificates' => $faker->sentences(3, true),
		'user_id' => 0
	];
});