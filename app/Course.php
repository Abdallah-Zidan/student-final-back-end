<?php

namespace App;

class Course extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'description'
	];

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleting(function ($course) {
			$course->departmentFaculties()->detach();
		});
	}

	/**
	 * Many-to-many relationship to the departmentFaculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function departmentFaculties()
	{
		return $this->belongsToMany(DepartmentFaculty::class, 'course_department_faculties')->withTimestamps();
	}
}