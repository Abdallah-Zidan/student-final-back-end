<?php

use App\Enums\UserType;
use App\Event;
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
			UserType::getTypeModel(UserType::TEACHING_STAFF),
			UserType::getTypeModel(UserType::COMPANY),
			UserType::getTypeModel(UserType::MODERATOR),
			UserType::getTypeModel(UserType::ADMIN)
		])->get()->each(function ($user) {
			factory(Event::class, 3)->create([
				'user_id' => $user->id
			]);
		});
	}
}