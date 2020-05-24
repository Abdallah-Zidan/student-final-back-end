<?php

use App\CourseDepartmentFaculty;
use App\User;
use Illuminate\Database\Seeder;

class CourseDepartmentFacultyUserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::whereIn('profileable_type', [
			'student',
			'teaching_staff'
		])->get()->each(function ($user) {
			CourseDepartmentFaculty::inRandomOrder()->take(10)->get()->each(function ($course_department_faculty) use ($user) {
				$user->courseDepartmentFaculties()->attach($course_department_faculty);
			});
		});
	}
}