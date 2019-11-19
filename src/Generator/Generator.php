<?php declare(strict_types = 1);

namespace Dag\Generator;

use Dag\Generator\Impl\IGenerator;

final class Generator
{

	/** @var IGenerator[] */
	private $generators = [];

	public function add(string $name, IGenerator $generator): void
	{
		$this->generators[$name] = $generator;
	}

	/**
	 * @return mixed[]
	 */
	public function generate(string $name, $content): array
	{
		return $this->generators[$name]->generate($content);
	}

}
