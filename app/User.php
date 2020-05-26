<?php

namespace App;

use App\Enums\UserGender;
use App\Notifications\VerifyEmailQueued;
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
		'name', 'email', 'password', 'gender', 'blocked', 'address', 'mobile', 'avatar', 'profileable_type', 'profileable_id'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', 'profileable_type', 'profileable_id'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'blocked' => 'boolean'
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
	 * Sets the user's password hash.
	 *
	 * @param string $value The user's password as *plain text*.
	 *
	 * @return void
	 */
	public function setPasswordAttribute(string $value)
	{
		$this->attributes['password'] = Hash::make($value);
	}

	/**
	 * Gets the user's gender as a StudlyCase.
	 *
	 * @param int $value the gender value.
	 *
	 * @return string|null
	 */
	public function getGenderAttribute(int $value)
	{
		return UserGender::getGenderString($value);
	}

	/**
	 * Gets the user's avatar image as a url.
	 *
	 * @param $value the avatar image path.
	 *
	 * @return string
	 */
	public function getAvatarAttribute($value)
	{
		if ($value)
			return request()->getSchemeAndHttpHost() . '/uploads/' . $value;

		return null;
	}

	/**
	 * Gets the user's type as a StudlyCase.
	 *
	 * @return string|null
	 */
	public function getTypeAttribute()
	{
		$value = $this->attributes['profileable_type'];
		$type = Str::after($value, 'App\\');
		$type = Str::before($type, 'Profile');

		return $type ?: null;
	}

	/**
	 * Send the given notification.
	 *
	 * @return void
	 */
	public function sendEmailVerificationNotification()
	{
		$this->notify(new VerifyEmailQueued);
	}

	/**
	 * One-to-one relationship to the profile.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 *
	 * @throws \Exception If the user's type is *null*, or the class name is not found.
	 */
	public function profileable()
	{
		return $this->morphTo();
	}

	/**
	 * One-to-many relationship to the resources.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function resources()
	{
		return $this->hasMany(Resource::class);
	}

	/**
	 * Many-to-many relationship to the departmentFaculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function departmentFaculties()
	{
		return $this->belongsToMany(DepartmentFaculty::class, 'department_faculty_users')->withTimestamps();
	}

	/**
	 * Many-to-many relationship to the courseDepartmentFaculties.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function courseDepartmentFaculties()
	{
		return $this->belongsToMany(CourseDepartmentFaculty::class, 'course_department_faculty_users')->withTimestamps();
	}

	/**
	 * One-to-many relationship to the posts.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function posts()
	{
		return $this->hasMany(Post::class);
	}

	/**
	 * One-to-many relationship to the questions.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function questions()
	{
		return $this->hasMany(Question::class);
	}

	/**
	 * One-to-many relationship to the events.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function events()
	{
		return $this->hasMany(Event::class);
	}

	/**
	 * One-to-many relationship to the comments.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 *
	 */
	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	/**
	 * Many-to-many relationship to the rates.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 *
	 */
	public function rates()
	{
		return $this->belongsToMany(Comment::class, 'rates')
					->withPivot('rate')
					->withTimestamps();
	}
}