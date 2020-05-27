<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		app('model-cache')->runDisabled(function () {
			$this->call(UniversitySeeder::class);
			$this->call(FacultySeeder::class);
			$this->call(DepartmentSeeder::class);
			$this->call(UserSeeder::class);
			$this->call(DepartmentFacultySeeder::class);
			$this->call(CourseSeeder::class);
			$this->call(CourseDepartmentFacultySeeder::class);
			$this->call(DepartmentFacultyUserSeeder::class);
			$this->call(CourseDepartmentFacultyUserSeeder::class);
			$this->call(EventSeeder::class);
			$this->call(PostSeeder::class);
			$this->call(QuestionSeeder::class);
			$this->call(TagSeeder::class);
			$this->call(QuestionTagSeeder::class);
			$this->call(CommentSeeder::class);
			$this->call(RateSeeder::class);
			$this->call(FileSeeder::class);
		});
	}
}