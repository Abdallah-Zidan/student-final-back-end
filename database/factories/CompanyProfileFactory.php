<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CompanyProfile;
use Faker\Generator as Faker;

$factory->define(CompanyProfile::class, function (Faker $faker) {
	return [
		'fax' => $faker->e164PhoneNumber,
		'description' => $faker->sentences(3, true),
		'website' => $faker->url
	];
});