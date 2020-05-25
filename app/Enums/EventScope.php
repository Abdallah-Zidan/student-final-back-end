<?php

namespace App\Enums;

class EventScope
{
	const DEPARTMENT = 0;
	const FACULTY = 1;
	const UNIVERSITY = 2;
	const ALL = 3;

	/**
	 * The event available scopes.
	 *
	 * @var array
	 */
	private static $scopes = [
		'Department',
		'Faculty',
		'University',
		'All'
	];

	/**
	 * Gets the scope from an integer value.
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
}