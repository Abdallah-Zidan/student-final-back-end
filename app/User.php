<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'mobile', 'avatar'
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
}