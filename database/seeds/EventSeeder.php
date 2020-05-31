<?php

use App\Enums\UserType;
use App\Event;
use App\Faculty;
use App\University;
use App\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::whereIn('profileable_type', [
			UserType::getTypeModel(UserType::STUDENT),
			UserType::getTypeModel(UserType::TEACHING_STAFF)
		])->get()->each(function ($user) {
			$user->departmentFaculties->load('faculty.university')->each(function ($department_faculty) use ($user) {
				factory(Event::class)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($department_faculty->faculty),
					'scopeable_id' => $department_faculty->faculty->id
				]);

				factory(Event::class)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($department_faculty->faculty->university),
					'scopeable_id' => $department_faculty->faculty->university->id
				]);

				factory(Event::class)->create([
					'user_id' => $user->id,
					'scopeable_type' => 'all'
				]);
			});
		});

		User::where('profileable_type', UserType::getTypeModel(UserType::COMPANY))->get()->each(function ($user) {
			Faculty::inRandomOrder()->take(5)->get()->each(function ($faculty) use ($user) {
				factory(Event::class)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($faculty),
					'scopeable_id' => $faculty->id
				]);
			});

			University::inRandomOrder()->take(5)->get()->each(function ($university) use ($user) {
				factory(Event::class)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($university),
					'scopeable_id' => $university->id
				]);
			});

			factory(Event::class)->create([
				'user_id' => $user->id,
				'scopeable_type' => 'all'
			]);
		});

		User::with('profileable.faculty.university')->where('profileable_type', UserType::getTypeModel(UserType::MODERATOR))->get()->each(function ($user) {
			factory(Event::class)->create([
				'user_id' => $user->id,
				'scopeable_type' => get_class($user->profileable->faculty),
				'scopeable_id' => $user->profileable->faculty->id
			]);

			factory(Event::class)->create([
				'user_id' => $user->id,
				'scopeable_type' => get_class($user->profileable->faculty->university),
				'scopeable_id' => $user->profileable->faculty->university->id
			]);

			factory(Event::class)->create([
				'user_id' => $user->id,
				'scopeable_type' => 'all'
			]);
		});
	}
}