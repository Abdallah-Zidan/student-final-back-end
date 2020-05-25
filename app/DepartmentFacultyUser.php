<?php

namespace App;

class DepartmentFacultyUser extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'department_faculty_id', 'user_id'
	];

	/**
	 * Many-to-many relationship to the departmentFaculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function departmentFaculties()
	{
		return $this->belongsToMany(User::class, 'department_faculties')->withTimestamps();
	}

	/**
	 * Many-to-many relationship to the users.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function users()
	{
		return $this->belongsToMany(User::class, 'department_faculty_users')->withTimestamps();
	}
}