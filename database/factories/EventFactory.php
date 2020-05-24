<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence,
		'body' => $faker->sentences(3, true),
		'type' => $faker->randomElement(Event::$types),
		'scope' => $faker->randomElement(Event::$scopes),
		'start_date' => $faker->dateTime(),
		'end_date' => $faker->dateTime('+7 days'),
		'user_id' => 0
	];
});