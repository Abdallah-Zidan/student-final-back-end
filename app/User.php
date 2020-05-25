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
		'name', 'email', 'password', 'gender', 'blocked', 'address', 'mobile', 'avatar', 'type'
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
	 * The user available genders.
	 *
	 * @var array
	 */
	public static $genders = [
		'Male',
		'Female'
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
	 * Gets the gender from an integer value.
	 *
	 * @param int $value the gender value equivalent.
	 *
	 * @return string|null
	 */
	public static function getGenderFromValue(int $value)
	{
		if ($value >= count(static::$genders))
			return null;

		return static::$genders[$value];
	}

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
	 * Gets the user's gender.
	 *
	 * @return string|null
	 */
	public function getGenderAttribute()
	{
		return static::$genders[$this->attributes['gender']];
	}

	/**
	 * Sets the user's gender.
	 *
	 * @param string $value The user's type as **Male** or **Female**.
	 *
	 * @return void
	 */
	public function setGenderAttribute(string $value)
	{
		$index = array_search($value, static::$genders);

		if ($index !== false)
			$this->attributes['gender'] = $index;
	}

	/**
	 * Gets the user's type as a StudlyCase.
	 *
	 * @return string|null
	 */
	public function getTypeAttribute()
	{
		return Str::studly($this->attributes['profileable_type']) ?: null;
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

		$this->attributes['profileable_type'] = $type;
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

	public function getAvatarAttribute($value)
	{
		return request()->getSchemeAndHttpHost() . '/uploads/' . $value;
	}
}
