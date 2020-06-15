<?php

namespace App;

class CourseDepartmentFaculty extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'course_id', 'department_faculty_id'
	];

	/**
	 * Many-to-many relationship to the users.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function users()
	{
		return $this->belongsToMany(User::class, 'course_department_faculty_users')->withTimestamps();
	}

	/**
	 * Many-to-one relationship to the departmentFaculty.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function departmentFaculty()
	{
		return $this->belongsTo(DepartmentFaculty::class);
	}

	/**
	 * Many-to-one relationship to the course.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function course()
	{
		return $this->belongsTo(Course::class);
	}
	
	public function coursePosts()
	{
		return $this->hasMany(CoursePost::class);
	}
}