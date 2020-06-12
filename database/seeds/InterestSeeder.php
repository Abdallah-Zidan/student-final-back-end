<?php

use App\Enums\EventType;
use App\Enums\UserType;
use App\Event;
use App\User;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Event::whereIn('type', [
			EventType::NORMAL,
			EventType::TRAINING,
			EventType::INTERNSHIP,
			EventType::JOB_OFFER
		])->get()->each(function ($event) {
			$users = User::whereIn('profileable_type', [
				UserType::getTypeModel(UserType::STUDENT),
				UserType::getTypeModel(UserType::TEACHING_STAFF)
			])->inRandomOrder()->take(5)->get();
			$event->interests()->attach($users);
		});
	}
}