<?php

namespace App\Enums;

class EventScope
{
	const FACULTY = 0;
	const UNIVERSITY = 1;
	const ALL = 2;

	/**
	 * The event available scopes.
	 *
	 * @var array
	 */
	private static $scopes = [
		'Faculty',
		'University',
		'All'
	];

	/**
	 * Get the scope from an integer value.
	 *
	 * @param int $value the scope value equivalent.
	 *
	 * @return string|null
	 */
	public static function getScopeString(int $value)
	{
		if ($value >= count(static::$scopes))
			return null;

		return static::$scopes[$value];
	}

	/**
	 * Get the scope from an integer value as **App\Model**.
	 *
	 * @param int $value the scope value equivalent.
	 *
	 * @return string|null
	 */
	public static function getScopeModel(int $value)
	{
		if ($value !== static::ALL && $scope = static::getScopeString($value))
			return 'App\\' . $scope;

		return null;
	}
}