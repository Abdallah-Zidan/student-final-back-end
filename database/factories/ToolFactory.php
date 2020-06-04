<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\ToolType;
use App\Tool;
use Faker\Generator as Faker;

$factory->define(Tool::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence,
		'body' => $faker->sentences(3, true),
		'type' => $faker->randomElement([ToolType::NEED, ToolType::OFFER]),
		'closed' => $faker->boolean,
		'faculty_id' => 0,
		'user_id' => 0
	];
});