<?php

namespace App\Enums;

class PostScope
{
	const DEPARTMENT = 0;
	const FACULTY = 1;
	const UNIVERSITY = 2;

	/**
	 * The post available scopes.
	 *
	 * @var array
	 */
	private static $scopes = [
		'Department',
		'Faculty',
		'University'
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

		return static::$scopes[$value] . ($value === static::DEPARTMENT ? 'Faculty' : '');
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
		if ($scope = static::getScopeString($value))
			return 'App\\' . $scope;

		return null;
	}
}