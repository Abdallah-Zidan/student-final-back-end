<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'birthdate', 'year', 'user_id'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'birthdate' => 'date',
	];

	/**
	 * One-to-one relationship to the user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 *
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}