<?php

namespace App\Enums;

class PostScope
{
	const YEAR = 0;
	const DEPARTMENT = 1;
	const FACULTY = 2;
	const ALL = 3;

	/**
	 * The post available scopes.
	 *
	 * @var array
	 */
	private static $scopes = [
		'Year',
		'Department',
		'Faculty',
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

	/**
	 * Gets a list of available scopes.
	 *
	 * @return array
	 */
	public static function getAllScopes()
	{
		return static::$scopes;
	}
}