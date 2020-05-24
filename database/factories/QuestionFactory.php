<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence,
		'body' => $faker->sentences(3, true),
		'user_id' => 0
	];
});