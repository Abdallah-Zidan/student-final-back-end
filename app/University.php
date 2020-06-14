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
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleting(function ($university) {
			$university->faculties->each->delete();
			$university->posts->each->delete();
			$university->events->each->delete();
		});
	}

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
}