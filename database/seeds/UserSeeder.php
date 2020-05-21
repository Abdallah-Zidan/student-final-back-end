<?php

use App\CompanyProfile;
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
		factory(User::class, 5)->create([
			'type' => 'admin'
		]);

		factory(User::class, 5)->create([
			'type' => 'moderator'
		]);

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