<?php

use App\DepartmentFaculty;
use App\User;
use Illuminate\Database\Seeder;

class DepartmentFacultyUserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::all()->each(function ($user) {
			DepartmentFaculty::inRandomOrder()->take(2)->get()->each(function ($department_faculty) use ($user) {
				$user->departmentFaculties()->attach($department_faculty);
			});
		});
	}
}