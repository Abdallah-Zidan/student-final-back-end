<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
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
	 * One-to-Many relationship to the faculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function faculties()
	{
		return $this->hasMany(Faculty::class);
	}
}