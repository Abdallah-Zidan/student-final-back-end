<?php

namespace App;

class University extends BaseModel
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
	 * One-to-Many relationship to the faculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function faculties()
	{
		return $this->hasMany(Faculty::class);
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
}