<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
	return [
		'path' => 'path/to/file.ext',
		'mime' => $faker->mimeType,
		'resourceable_type' => null,
		'resourceable_id' => 0
	];
});