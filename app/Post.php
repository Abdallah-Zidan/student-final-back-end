<?php

namespace App;

use Illuminate\Support\Str;

class Post extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'body', 'reported', 'user_id', 'scopeable_type', 'scopeable_id', 'year'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'scopeable_type', 'scopeable_id'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'reported' => 'boolean'
	];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [
		'scope'
	];

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleted(function ($post) {
			$post->comments()->delete();
			$post->files()->delete();
		});
	}

	/**
	 * Gets the post's scope as a StudlyCase.
	 *
	 * @return string|null
	 */
	public function getScopeAttribute()
	{
		$value = $this->attributes['scopeable_type'];
		$scope = Str::after($value, 'App\\');

		return $scope ?: null;
	}

	/**
	 * Many-to-one relationship to the user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Many-to-one relationship to the scope.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 *
	 */
	public function scopeable()
	{
		return $this->morphTo();
	}

	/**
	 * One-to-many relationship to the comments.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 *
	 */
	public function comments()
	{
		return $this->morphMany(Comment::class, 'parentable');
	}

	/**
	 * One-to-many relationship to the files.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 *
	 */
	public function files()
	{
		return $this->morphMany(File::class, 'resourceable');
	}
}