<?php

namespace App\Enums;

class UserGender
{
	const MALE = 0;
	const FEMALE = 1;

	/**
	 * The user available genders.
	 *
	 * @var array
	 */
	private static $genders = [
		'Male',
		'Female'
	];

	/**
	 * Get the gender from an integer value.
	 *
	 * @param int $value the gender value equivalent.
	 *
	 * @return string|null
	 */
	public static function getGenderString(int $value)
	{
		if ($value >= count(static::$genders))
			return null;

		return static::$genders[$value];
	}
}