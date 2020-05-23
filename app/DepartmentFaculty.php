<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentFaculty extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'department_id', 'faculty_id'
	];

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

	/**
	 * Many-to-one relationship to the faculty.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function faculty()
	{
		return $this->belongsTo(Faculty::class);
	}

	/**
	 * Many-to-one relationship to the department.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	/**
	 * Many-to-many relationship to the courses.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 *
	 */
	public function courses()
	{
		return $this->belongsToMany(Course::class, 'course_department_faculties')->withTimestamps();
	}
}