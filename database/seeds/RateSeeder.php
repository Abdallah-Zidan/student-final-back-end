<?php

use App\Comment;
use App\Question;
use App\User;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$rates = [-1, 1];
		Comment::where('parentable_type', Question::class)->get()->each(function ($comment) use ($rates) {
			User::inRandomOrder()->take(5)->get()->each(function ($user) use ($comment, $rates) {
				$comment->rates()->attach($user, [
					'rate' => $rates[array_rand($rates)]
				]);
			});
		});
	}
}