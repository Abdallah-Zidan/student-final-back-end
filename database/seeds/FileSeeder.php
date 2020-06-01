<?php

use App\Event;
use App\File;
use App\Post;
use App\Tool;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Post::all()->each(function ($post) {
			factory(File::class, 3)->create([
				'resourceable_type' => get_class($post),
				'resourceable_id' => $post->id
			]);
		});

		Event::all()->each(function ($event) {
			factory(File::class, 3)->create([
				'resourceable_type' => get_class($event),
				'resourceable_id' => $event->id
			]);
		});

		Tool::all()->each(function ($tool) {
			factory(File::class, 3)->create([
				'resourceable_type' => get_class($tool),
				'resourceable_id' => $tool->id
			]);
		});
	}
}