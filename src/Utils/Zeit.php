<?php declare(strict_types = 1);

namespace Dag\Utils;

class Zeit
{

	public static function now(): bool
	{
		return !empty($_ENV['NOW_REGION'] ?? false);
	}

}
