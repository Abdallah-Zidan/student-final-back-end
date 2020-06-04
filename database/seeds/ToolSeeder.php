<?php

use App\DepartmentFaculty;
use App\DepartmentFacultyUser;
use App\Faculty;
use App\Tool;
use App\User;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Faculty::all()->each(function ($faculty) {
			$department_faculties = DepartmentFaculty::where('faculty_id', $faculty->id)->get();
			$department_faculty_users = DepartmentFacultyUser::whereIn('department_faculty_id', $department_faculties->pluck('id'));
			User::whereIn('id', $department_faculty_users->pluck('user_id'))->inRandomOrder()->take(15)->get()->each(function ($user) use ($faculty) {
				factory(Tool::class)->create([
					'faculty_id' => $faculty->id,
					'user_id' => $user->id
				]);
			});
		});
	}
}