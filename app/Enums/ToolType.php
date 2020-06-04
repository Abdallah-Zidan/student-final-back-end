<?php

namespace App\Enums;

class ToolType
{
	const NEED = 0;
	const OFFER = 1;

	/**
	 * The tool available types.
	 *
	 * @var array
	 */
	public static $types = [
		'Need',
		'Offer'
	];

	/**
	 * Get the type from an integer value.
	 *
	 * @param int $value the type value equivalent.
	 *
	 * @return string|null
	 */
	public static function getTypeString(int $value)
	{
		if ($value >= count(static::$types))
			return null;

		return static::$types[$value];
	}
}