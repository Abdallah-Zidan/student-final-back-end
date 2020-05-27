<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class File extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'path', 'mime', 'resourceable_type', 'resourceable_id'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'path', 'resourceable_type', 'resourceable_id'
	];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [
		'url'
	];

	/**
	 * Perform any actions required after the model boots.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::deleted(function ($file) {
			Storage::disk('local')->delete($file->path);
		});
	}

	/**
	 * Gets the user's avatar image as a url.
	 *
	 * @param $value the avatar image path.
	 *
	 * @return string
	 */
	public function getUrlAttribute()
	{
		return request()->getSchemeAndHttpHost() . '/uploads/' . $this->attributes['path'];
	}

	/**
	 * Many-to-one relationship to the resource.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 *
	 */
	public function resourceable()
	{
		return $this->morphTo();
	}
}