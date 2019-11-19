<?php declare(strict_types = 1);

namespace Dag\Utils;

use Contributte\Utils\Arrays as CArrays;

class Arrays extends CArrays
{

	public static function toArray($data): array
	{
		return json_decode(json_encode($data), true);
	}

}
