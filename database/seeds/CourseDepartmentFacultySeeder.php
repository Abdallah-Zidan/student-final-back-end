<?php

use App\Course;
use App\CourseDepartmentFaculty;
use App\DepartmentFaculty;
use Illuminate\Database\Seeder;

class CourseDepartmentFacultySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DepartmentFaculty::all()->each(function ($department_faculty) {
			Course::inRandomOrder()->take(5)->get()->each(function ($course) use ($department_faculty) {
				factory(CourseDepartmentFaculty::class)->create([
					'course_id' => $course->id,
					'department_faculty_id' => $department_faculty->id
				]);
			});
		});
	}
}