<?php

use App\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(University::class, 10)->create();
	}
}