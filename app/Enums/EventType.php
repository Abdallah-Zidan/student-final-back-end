<?php

namespace App\Enums;

class EventType
{
	const NORMAL = 0;
	const TRAINING = 1;
	const INTERNSHIP = 2;
	const ANNOUNCEMENT = 3;
	const JOB_OFFER = 4;

	/**
	 * The event available types.
	 *
	 * @var array
	 */
	private static $types = [
		'Normal',
		'Training',
		'Internship',
		'Announcement',
		'JobOffer'
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