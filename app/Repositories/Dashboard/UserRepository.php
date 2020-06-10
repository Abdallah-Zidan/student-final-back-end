<?php

namespace App\Repositories\Dashboard;

use App\CompanyProfile;
use App\CourseDepartmentFaculty;
use App\DepartmentFaculty;
use App\DepartmentFacultyUser;
use App\Enums\UserType;
use App\Faculty;
use App\ModeratorProfile;
use App\StudentProfile;
use App\TeachingStaffProfile;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
	/**
	 * Get all users.
	 *
	 * @param \App\User $user The user object.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll(User $user, int $items = 10)
	{
		if ($user->type === UserType::getTypeString(UserType::MODERATOR))
			return $this->getAllForMoedrator($user->profileable->faculty, $items);
		else if ($user->type === UserType::getTypeString(UserType::ADMIN))
			return $this->getAllForAdmin($items);

		return new LengthAwarePaginator([], 0, 10, 1, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page'
		]);
	}

	/**
	 * Create a user.
	 *
	 * @param array $data The user data.
	 * @param array $profile_data The user profile data.
	 *
	 * @return \App\User
	 */
	public function create(array $data, array $profile_data)
	{
		if ($data['type'] == UserType::STUDENT)
			$profile = StudentProfile::create($profile_data);
		else if ($data['type'] == UserType::TEACHING_STAFF)
			$profile = TeachingStaffProfile::create($profile_data);
		else if ($data['type'] == UserType::COMPANY)
			$profile = CompanyProfile::create($profile_data);
		else if ($data['type'] == UserType::MODERATOR)
			$profile = ModeratorProfile::create($profile_data);

		if (array_key_exists('avatar', $data))
			$data['avatar'] = Storage::disk('local')->put('images/users', $data['avatar']);

		if ($data['type'] == UserType::ADMIN)
			$user = User::create($data);
		else
			$user = $profile->user()->create($data);

		return $user;
	}

	/**
	 * Update an existing user.
	 *
	 * @param \App\User $user The user object.
	 * @param array $data The user data.
	 * @param array $profile_data The user profile data.
	 *
	 * @return void
	 */
	public function update(User $user, array $data, array $profile_data)
	{
		if ($user->type !== UserType::getTypeString(UserType::ADMIN))
			$user->profileable()->update($profile_data);

		if (array_key_exists('avatar', $data))
		{
			Storage::disk('local')->delete($user->avatar);
			$data['avatar'] = Storage::disk('local')->put('images/users', $data['avatar']);
		}

		$user->update($data);
	}

	/**
	 * Delete an existing user.
	 *
	 * @param \App\User $user The user object.
	 *
	 * @return void
	 */
	public function delete(User $user)
	{
		if ($user->type !== UserType::getTypeString(UserType::ADMIN))
			$user->profileable()->delete();

		$user->delete();
	}

	/**
	 * Attach department to user.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\DepartmentFaculty $department_Faculty The department_Faculty object.
	 *
	 * @return void
	 */
	public function attachDepartment(User $user, DepartmentFaculty $department_Faculty)
	{
		if (!$user->departmentFaculties()->find($department_Faculty))
			$user->departmentFaculties()->attach($department_Faculty);
	}

	/**
	 * Detach department from user.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\DepartmentFaculty $department_Faculty The department_Faculty object.
	 *
	 * @return void
	 */
	public function detachDepartment(User $user, DepartmentFaculty $department_Faculty)
	{
		$user->departmentFaculties()->detach($department_Faculty);
	}

	/**
	 * Attach course to user.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\CourseDepartmentFaculty $course_department_faculty The course_department_faculty object.
	 *
	 * @return void
	 */
	public function attachCourse(User $user, CourseDepartmentFaculty $course_department_faculty)
	{
		if (!$user->courseDepartmentFaculties()->find($course_department_faculty))
			$user->courseDepartmentFaculties()->attach($course_department_faculty);
	}

	/**
	 * Detach course from user.
	 *
	 * @param \App\User $user The user object.
	 * @param \App\CourseDepartmentFaculty $course_department_faculty The course_department_faculty object.
	 *
	 * @return void
	 */
	public function detachCourse(User $user, CourseDepartmentFaculty $course_department_faculty)
	{
		$user->courseDepartmentFaculties()->detach($course_department_faculty);
	}

	/**
	 * Get all users related to the moedrator groups.
	 *
	 * @param \App\Faculty $faculty The faculty object.
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForMoedrator(Faculty $faculty, int $items)
	{
		$department_faculties = DepartmentFaculty::where('faculty_id', $faculty->id)->get();
		$department_faculty_users = DepartmentFacultyUser::whereIn('department_faculty_id', $department_faculties->pluck('id'))->get();
		$moderator_profiles = ModeratorProfile::where('faculty_id', $faculty->id)->get();

		$users = User::whereIn('id', $department_faculty_users->pluck('user_id'))
					->orWhere(function ($query) use ($moderator_profiles) {
						$query->where('profileable_type', UserType::getTypeModel(UserType::MODERATOR))
							->whereIn('profileable_id', $moderator_profiles->pluck('id'));
					})->paginate($items);

		$users->whereIn('profileable_type', [
			UserType::getTypeModel(UserType::STUDENT),
			UserType::getTypeModel(UserType::TEACHING_STAFF),
			UserType::getTypeModel(UserType::MODERATOR)
		])->load('profileable');

		$users->where('profileable_type', UserType::getTypeModel(UserType::MODERATOR))->load('profileable.faculty.university');

		return $users;
	}

	/**
	 * Get all users.
	 *
	 * @param int $items The items count per page.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	private function getAllForAdmin(int $items)
	{
		$users = User::paginate($items);

		$users->whereIn('profileable_type', [
			UserType::getTypeModel(UserType::STUDENT),
			UserType::getTypeModel(UserType::TEACHING_STAFF),
			UserType::getTypeModel(UserType::COMPANY),
			UserType::getTypeModel(UserType::MODERATOR)
		])->load('profileable');

		$users->where('profileable_type', UserType::getTypeModel(UserType::MODERATOR))->load('profileable.faculty.university');

		return $users;
	}
}