<?php

namespace App\Enums;

class TagScope
{
	const TOOL = 0;
	const QUESTION = 1;

	/**
	 * The event available types.
	 *
	 * @var array
	 */
	public static $types = [
		'tool',
		'question',
	];

	/**
	 * Get the type from an integer value.
	 *
	 * @param int $value the type value equivalent.
	 *
	 * @return string|null
	 */
	public static function getScopeString(int $value)
	{
		if ($value >= count(static::$types))
			return null;

		return static::$types[$value];
	}
}