<?php

namespace App;

class File extends BaseModel
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'path', 'mime', 'resource_id'
	];

	/**
	 * Many-to-one relationship to the user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function resource()
	{
		return $this->belongsTo(Resource::class);
	}
}