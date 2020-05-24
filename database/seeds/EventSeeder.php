<?php

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
			'company',
			'teaching_staff',
			'moderator',
			'admin'
		])->get()->each(function ($user) {
			factory(Event::class, 3)->create([
				'user_id' => $user->id
			]);
		});
	}
}