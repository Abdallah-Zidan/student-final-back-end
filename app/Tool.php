<?php

namespace App;

use App\Enums\ToolType;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 'body', 'type', 'faculty_id', 'user_id'
	];

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleting(function ($tool) {
			$tool->comments()->delete();
			$tool->files->each->delete();
			$tool->tags()->detach();
		});
	}

	/**
	 * Get the tool's type as a StudlyCase.
	 *
	 * @param int $value the type value.
	 *
	 * @return string|null
	 */
	public function getTypeAttribute(int $value)
	{
		return ToolType::getTypeString($value);
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
	 * Many-to-one relationship to the faculty.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function faculty()
	{
		return $this->belongsTo(Faculty::class);
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

	/**
	 * Many-to-many relationship to the tags.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'tag_tools')->withTimestamps();
	}
}