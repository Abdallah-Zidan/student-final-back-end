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
	 * Many-to-many relationship to the questions.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function questions()
	{
		return $this->belongsToMany(Question::class, 'question_tags')->withTimestamps();
	}
}