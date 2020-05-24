<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 'body', 'type', 'scope', 'start_date', 'end_date', 'user_id'
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
	 * The event available types.
	 *
	 * @var array
	 */
	public static $types = [
		'Normal',
		'Training',
		'Internship',
		'Announcement',
		'JobOffer'
	];

	/**
	 * The event available scopes.
	 *
	 * @var array
	 */
	public static $scopes = [
		'all',
		'university',
		'faculty',
		'department'
	];

	/**
	 * Gets the type from an integer value.
	 *
	 * @param int $value the type value equivalent.
	 *
	 * @return string|null
	 */
	public static function getTypeFromValue(int $value)
	{
		if ($value >= count(static::$types))
			return null;

		return static::$types[$value];
	}

	/**
	 * Gets the event's type as a StudlyCase.
	 *
	 * @return string|null
	 */
	public function getTypeAttribute()
	{
		return Str::studly(static::$types[$this->attributes['type']]);
	}

	/**
	 * Sets the event's type as an integer.
	 *
	 * @param string $value The event's type as a string.
	 *
	 * @return void
	 */
	public function setTypeAttribute(string $value)
	{
		$index = array_search($value, static::$types);

		if ($index !== false)
			$this->attributes['type'] = $index;
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
	 * One-to-many relationship to the comments.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function comments()
	{
		return $this->morphMany(Comment::class, 'parentable');
	}
}