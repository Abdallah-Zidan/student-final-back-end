<?php

use App\Question;
use App\Tag;
use Illuminate\Database\Seeder;

class QuestionTagSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Question::all()->each(function ($question) {
			$tags = Tag::inRandomOrder()->take(3)->get();
			$question->tags()->attach($tags);
		});
	}
}