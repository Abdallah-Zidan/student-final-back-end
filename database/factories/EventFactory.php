<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\EventScope;
use App\Enums\EventType;
use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence,
		'body' => $faker->sentences(3, true),
		'type' => $faker->randomElement([EventType::NORMAL, EventType::TRAINING, EventType::INTERNSHIP, EventType::ANNOUNCEMENT, EventType::JOB_OFFER]),
		'scope' => $faker->randomElement([EventScope::DEPARTMENT, EventScope::FACULTY, EventScope::UNIVERSITY, EventScope::ALL]),
		'start_date' => now(),
		'end_date' => now()->addDays(7),
		'user_id' => 0
	];
});