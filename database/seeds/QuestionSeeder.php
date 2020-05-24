<?php

use App\Question;
use App\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::all()->each(function ($user) {
			factory(Question::class, 5)->create([
				'user_id' => $user->id
			]);
		});
	}
}