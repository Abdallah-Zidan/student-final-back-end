<?php

namespace App;

use App\Enums\EventType;
use Illuminate\Support\Str;

class Event extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 'body', 'type', 'start_date', 'end_date', 'user_id', 'scopeable_type', 'scopeable_id'
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
		'start_date' => 'datetime',
		'end_date' => 'datetime'
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
		static::deleting(function ($event) {
			$event->comments()->delete();
			$event->files->each->delete();
		});
	}

	/**
	 * Get the event's type as a StudlyCase.
	 *
	 * @param int $value the type value.
	 *
	 * @return string|null
	 */
	public function getTypeAttribute(int $value)
	{
		return EventType::getTypeString($value);
	}

	/**
	 * Get the event's scope as a StudlyCase.
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