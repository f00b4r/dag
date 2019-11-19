<?php declare(strict_types = 1);

namespace Dag\Generator\Impl;

interface IGenerator
{

	public const FALLBACK = '*';
	public const EXCEPTION = '!';

	/**
	 * @param mixed $input
	 * @return mixed[]
	 */
	public function generate($input): array;

}
