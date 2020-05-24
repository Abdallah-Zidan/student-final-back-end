<?php

use App\CompanyProfile;
use App\Faculty;
use App\ModeratorProfile;
use App\StudentProfile;
use App\TeachingStaffProfile;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(User::class)->create([
			'name' => 'Admin',
			'email' => 'admin@domain.com',
			'gender' => User::getGenderFromValue(0),
			'blocked' => false,
			'type' => 'admin'
		]);
		factory(User::class, 4)->create([
			'type' => 'admin'
		]);

		Faculty::all()->each(function ($faculty) {
			$user = factory(User::class)->create([
				'blocked' => false,
				'type' => ModeratorProfile::class
			]);
			factory(ModeratorProfile::class)->create([
				'faculty_id' => $faculty->id,
				'user_id' => $user->id
			]);
		});

		factory(User::class, 5)->create([
			'type' => StudentProfile::class
		])->each(function ($user) {
			factory(StudentProfile::class)->create([
				'user_id' => $user->id
			]);
		});

		factory(User::class, 5)->create([
			'type' => CompanyProfile::class
		])->each(function ($user) {
			factory(CompanyProfile::class)->create([
				'user_id' => $user->id
			]);
		});

		factory(User::class, 5)->create([
			'type' => TeachingStaffProfile::class
		])->each(function ($user) {
			factory(TeachingStaffProfile::class)->create([
				'user_id' => $user->id
			]);
		});
	}
}