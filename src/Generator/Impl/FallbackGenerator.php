<?php declare(strict_types = 1);

namespace Dag\Generator\Impl;

final class FallbackGenerator implements IGenerator
{

	/**
	 * {@inheritDoc}
	 */
	public function generate($input): array
	{
		return [
			'error' => 'Endpoint not found',
		];
	}

}
