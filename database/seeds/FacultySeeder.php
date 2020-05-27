<?php

use App\Faculty;
use App\University;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		University::all()->each(function ($university) {
			factory(Faculty::class, 5)->create([
				'university_id' => $university->id
			]);
		});
	}
}