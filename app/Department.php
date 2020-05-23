<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name'
	];

	/**
	 * Many-to-many relationship to the faculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function faculties()
	{
		return $this->belongsToMany(Faculty::class, 'department_faculties')->withTimestamps();
	}

	/**
	 * One-to-many relationship to the courseDepartmentFaculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 *
	 */
	public function courseDepartmentFaculties()
	{
		return $this->hasManyThrough(CourseDepartmentFaculty::class, DepartmentFaculty::class);
	}
}