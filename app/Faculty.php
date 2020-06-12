<?php

namespace App;

class Faculty extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'university_id'
	];

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleting(function ($faculty) {
			$faculty->moderators()->delete();
			$faculty->departments()->detach();
			$faculty->courseDepartmentFaculties()->delete();
			$faculty->posts->delete();
			$faculty->events->delete();
			$faculty->tools->delete();
		});
	}

	/**
	 * One-to-many relationship to the moderators.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function moderators()
	{
		return $this->hasMany(ModeratorProfile::class);
	}

	/**
	 * Many-to-one relationship to the university.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function university()
	{
		return $this->belongsTo(University::class);
	}

	/**
	 * Many-to-many relationship to the departments.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function departments()
	{
		return $this->belongsToMany(Department::class, 'department_faculties')->withTimestamps();
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

	/**
	 * One-to-many relationship to the posts.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 *
	 */
	public function posts()
	{
		return $this->morphMany(Post::class, 'scopeable');
	}

	/**
	 * One-to-many relationship to the events.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 *
	 */
	public function events()
	{
		return $this->morphMany(Post::class, 'scopeable');
	}

	/**
	 * One-to-many relationship to the tools.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function tools()
	{
		return $this->hasMany(Tool::class);
	}
}