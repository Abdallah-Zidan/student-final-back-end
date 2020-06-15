<?php

namespace App\Enums;

class TagScope
{
	const TOOL = 0;
	const QUESTION = 1;
	const TUTORIAL=2;

	/**
	 * The tag available scopes.
	 *
	 * @var array
	 */
	public static $scopes = [
		'Tool',
		'Question',
		'Tutorial'
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
}