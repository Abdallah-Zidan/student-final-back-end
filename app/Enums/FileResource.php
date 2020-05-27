<?php

namespace App\Enums;

class FileResource
{
	const POST = 0;
	const EVENT = 1;

	/**
	 * The file available resources.
	 *
	 * @var array
	 */
	private static $resources = [
		'Post',
		'Event'
	];

	/**
	 * Gets the resource from an integer value.
	 *
	 * @param int $value the resource value equivalent.
	 *
	 * @return string|null
	 */
	public static function getResourceString(int $value)
	{
		if ($value >= count(static::$resources))
			return null;

		return static::$resources[$value];
	}
}