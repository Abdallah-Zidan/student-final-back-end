<?php

namespace App;

use App\Enums\EventScope;
use App\Enums\EventType;

class Event extends BaseModel
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
	 * @param int $value the scope value.
	 *
	 * @return string|null
	 */
	public function getScopeAttribute(int $value)
	{
		return EventScope::getScopeString($value);
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