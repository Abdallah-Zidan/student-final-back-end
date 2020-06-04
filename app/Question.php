<?php

namespace App;

class Question extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 'body', 'user_id'
	];

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleting(function ($question) {
			$question->comments()->delete();
			$question->tags()->detach();
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
	 * Many-to-many relationship to the tags.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'question_tags')->withTimestamps();
	}
}