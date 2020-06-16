<?php

use App\Course;
use App\CourseDepartmentFaculty;
use App\Department;
use App\DepartmentFaculty;
use App\Enums\UserType;
use App\Question;
use App\Tag;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class RealDatabaseSeeder2 extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		app('model-cache')->runDisabled(function () {
			Tag::insert([
				['name' => 'Java', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'C++', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'C#', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Jquery', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'JavaScript', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Angular', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'data structures', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Arabic', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'English', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'French', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Business', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'WordPress', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Food', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Writing', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Cars', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Music', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Games', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Movies', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Books', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Fitness', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Travel', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Entertainment ', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Fashion ', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Lifestyle ', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'DIY ', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Politics ', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Parenting ', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Pets ', 'created_at' => now(), 'updated_at' => now()],
			]);

			Course::insert([
				['name' => 'Java', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'C++', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'C#', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Jquery', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'JavaScript', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Angular', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'data structures', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Arabic', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'English', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'French', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
				['name' => 'Finance', 'description' => 's simply dummy text of the printing and typesetting industry.', 'created_at' => now(), 'updated_at' => now()],
			]);

			DepartmentFaculty::all()->each(function ($department_faculty) {
				Course::inRandomOrder()->take(3)->get()->each(function ($course) use ($department_faculty) {
					factory(CourseDepartmentFaculty::class)->create([
						'course_id' => $course->id,
						'department_faculty_id' => $department_faculty->id
					]);
				});
			});


			User::whereIn('profileable_type', [
				UserType::getTypeModel(UserType::STUDENT),
				UserType::getTypeModel(UserType::TEACHING_STAFF)
			])->get()->each(function ($user) {
				CourseDepartmentFaculty::inRandomOrder()->take(5)->get()->each(function ($course_department_faculty) use ($user) {
					$user->courseDepartmentFaculties()->attach($course_department_faculty);
				});
			});
		});
	}
}
