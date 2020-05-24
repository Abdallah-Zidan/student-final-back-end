<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
	return [
		'body' => $faker->sentences(3, true),
		'user_id' => 0,
		'parentable_type' => null,
		'parentable_id' => 0
	];
});