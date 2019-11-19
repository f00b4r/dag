<?php declare(strict_types = 1);

namespace Dag\Generator\Impl;

use Throwable;

final class ExceptionGenerator implements IGenerator
{

	/**
	 * @param Throwable $e
	 * @return mixed[]
	 */
	public function generate($e): array
	{
		return [
			'error' => $e->getMessage(),
		];
	}

}
