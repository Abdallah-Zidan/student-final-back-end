<?php

use App\CoursePost;
use App\Enums\UserType;
use App\User;
use Illuminate\Database\Seeder;

class CoursePostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereIn('profileable_type', [
			UserType::getTypeModel(UserType::TEACHING_STAFF)
		])->get()->each(function ($user) {
			factory(CoursePost::class, 3)->create([
				'user_id' => $user->id
			]);
		});
    }
}
