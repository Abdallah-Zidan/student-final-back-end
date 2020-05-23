<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'address', 'mobile', 'avatar', 'type'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', 'profileable_type'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [
		'type'
	];

	/**
	 * The user available types.
	 *
	 * @var array
	 */
	public static $types = [
		'Student',
		'Company',
		'TeachingStaff',
		'Moderator',
		'Admin'
	];

	/**
	 * Gets the type from an integer value.
	 *
	 * @param int $value the type value equivalent.
	 *
	 * @return string|null
	 */
	public static function getTypeFromValue(int $value)
	{
		if ($value >= count(static::$types))
			return null;

		return static::$types[$value];
	}

	/**
	 * Gets the user's type as a StudlyCase.
	 *
	 * @return string|null
	 */
	public function getTypeAttribute()
	{
		return Str::studly($this->profileable_type) ?: null;
	}

	/**
	 * Sets the user's type as a snake_case.
	 *
	 * @param string $value The user's type as **App\Model**.
	 *
	 * @return void
	 */
	public function setTypeAttribute(string $value)
	{
		$type = Str::after($value, 'App\\');
		$type = Str::before($type, 'Profile');
		$type = Str::snake($type);

		$this->profileable_type = $type;
	}

	/**
	 * One-to-one relationship to the profile.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 *
	 * @throws \Exception If the user's type is *null*, or the class name is not found.
	 */
	public function profile()
	{
		$type = $this->type ? ('App\\' . $this->type . 'Profile') : null;

		return $this->hasOne($type);
	}

	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}
}