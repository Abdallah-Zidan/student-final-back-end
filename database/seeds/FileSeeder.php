<?php

use App\File;
use App\Resource;
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
		Resource::all()->each(function ($resource) {
			factory(File::class, 3)->create([
				'resource_id' => $resource->id
			]);
		});
	}
}