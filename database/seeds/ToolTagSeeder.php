<?php

use App\Tag;
use App\Tool;
use Illuminate\Database\Seeder;

class ToolTagSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Tool::all()->each(function ($tool) {
			$tags = Tag::inRandomOrder()->take(3)->get();
			$tool->tags()->attach($tags);
		});
	}
}