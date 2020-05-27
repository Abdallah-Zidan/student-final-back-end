<?php

use App\Enums\UserType;
use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
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
			$faculties = new Collection;

			$user->departmentFaculties->each(function ($department_faculty) use ($user, $faculties) {
				$faculty = $department_faculty->faculty;

				if (!$faculties->contains($faculty))
					$faculties->push($faculty);

				factory(Post::class, 2)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($department_faculty),
					'scopeable_id' => $department_faculty->id
				]);
			});

			$faculties->each(function ($faculty) use ($user) {
				factory(Post::class, 2)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($faculty),
					'scopeable_id' => $faculty->id
				]);
				factory(Post::class, 2)->create([
					'user_id' => $user->id,
					'scopeable_type' => get_class($faculty->university),
					'scopeable_id' => $faculty->university->id
				]);
			});
		});
	}
}