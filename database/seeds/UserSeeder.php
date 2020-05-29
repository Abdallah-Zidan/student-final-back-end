<?php

use App\CompanyProfile;
use App\Enums\UserGender;
use App\Enums\UserType;
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
			'gender' => UserGender::MALE,
			'blocked' => false,
			'profileable_type' => UserType::getTypeModel(UserType::ADMIN)
		]);
		factory(User::class, 4)->create([
			'profileable_type' => UserType::getTypeModel(UserType::ADMIN)
		]);

		Faculty::all()->each(function ($faculty) {
			$profile = factory(ModeratorProfile::class)->create([
				'faculty_id' => $faculty->id
			]);
			factory(User::class)->create([
				'blocked' => false,
				'profileable_type' => get_class($profile),
				'profileable_id' => $profile->id
			]);
		});

		factory(CompanyProfile::class, 5)->create()->each(function ($profile) {
			factory(User::class)->create([
				'profileable_type' => get_class($profile),
				'profileable_id' => $profile->id
			]);
		});

		factory(StudentProfile::class, 50)->create()->each(function ($profile) {
			factory(User::class)->create([
				'profileable_type' => get_class($profile),
				'profileable_id' => $profile->id
			]);
		});

		factory(TeachingStaffProfile::class, 10)->create()->each(function ($profile) {
			factory(User::class)->create([
				'profileable_type' => get_class($profile),
				'profileable_id' => $profile->id
			]);
		});
	}
}