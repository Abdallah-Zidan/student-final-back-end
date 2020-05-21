<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'fax', 'description', 'website', 'user_id'
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