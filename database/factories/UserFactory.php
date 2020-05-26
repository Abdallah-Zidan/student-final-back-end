<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\UserGender;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
	$gender = $faker->randomElement([UserGender::MALE, UserGender::FEMALE]);

	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'email_verified_at' => now(),
		'password' => '123456',
		'gender' => $gender,
		'blocked' => $faker->boolean,
		'address' => $faker->address,
		'mobile' => $faker->e164PhoneNumber,
		'avatar' => $gender == UserGender::MALE ? 'images/users/default_male.png' : 'images/users/default_female.png',
		'profileable_type' => null,
		'profileable_id' => 0,
		'remember_token' => Str::random(10)
	];
});