<?php

use App\DepartmentFaculty;
use App\Enums\UserType;
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
		User::whereIn('profileable_type', [
			UserType::getTypeModel(UserType::STUDENT),
			UserType::getTypeModel(UserType::TEACHING_STAFF)
		])->get()->each(function ($user) {
			$count = 2;

			if ($user->type === UserType::getTypeString(UserType::STUDENT))
				$count = rand(1, 2);
			else if ($user->type === UserType::getTypeString(UserType::TEACHING_STAFF))
				$count = 4;

			DepartmentFaculty::inRandomOrder()->take($count)->get()->each(function ($department_faculty) use ($user) {
				$user->departmentFaculties()->attach($department_faculty);
			});
		});
	}
}