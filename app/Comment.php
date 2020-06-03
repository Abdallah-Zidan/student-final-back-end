<?php

namespace App;

class Comment extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'body', 'user_id', 'parentable_type', 'parentable_id'
	];

	/**
	 * Model Events
	 * 
	 */
	protected static function booted()
    {
        static::deleting(function ($comment) {
            $comment->replies()->delete();
        });
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
	 * Many-to-one relationship to the parent.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 *
	 */
	public function parent()
	{
		return $this->morphTo('parentable');
	}

	/**
	 * One-to-many relationship to the replies.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 *
	 */
	public function replies()
	{
		return $this->morphMany(Comment::class, 'parentable');
	}

	/**
	 * Many-to-many relationship to the rates.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function rates()
	{
		return $this->belongsToMany(User::class, 'rates')
					->withPivot('rate')
					->withTimestamps();
	}
}