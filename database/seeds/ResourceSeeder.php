<?php

use App\Resource;
use App\User;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::all()->each(function ($user) {
			factory(Resource::class, 5)->create([
				'user_id' => $user->id
			]);
		});
	}
}