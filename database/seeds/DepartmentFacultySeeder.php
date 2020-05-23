<?php

use App\Department;
use App\DepartmentFaculty;
use App\Faculty;
use Illuminate\Database\Seeder;

class DepartmentFacultySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Faculty::all()->each(function ($faculty) {
			Department::inRandomOrder()->take(5)->get()->each(function ($department) use ($faculty) {
				factory(DepartmentFaculty::class)->create([
					'department_id' => $department->id,
					'faculty_id' => $faculty->id
				]);
			});
		});
	}
}