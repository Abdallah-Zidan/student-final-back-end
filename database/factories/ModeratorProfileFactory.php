<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ModeratorProfile;
use Faker\Generator as Faker;

$factory->define(ModeratorProfile::class, function (Faker $faker) {
	return [
		'faculty_id' => 0,
		'user_id' => 0
	];
});