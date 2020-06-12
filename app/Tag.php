<?php

namespace App;

class Tag extends BaseModel
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
		static::deleting(function ($tag) {
			$tag->questions()->detach();
			$tag->tools()->detach();
		});
	}

	/**
	 * Many-to-many relationship to the questions.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function questions()
	{
		return $this->belongsToMany(Question::class, 'question_tags')->withTimestamps();
	}

	/**
	 * Many-to-many relationship to the tools.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function tools()
	{
		return $this->belongsToMany(Tool::class, 'tag_tools')->withTimestamps();
	}
}