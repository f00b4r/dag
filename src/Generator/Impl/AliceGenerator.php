<?php declare(strict_types = 1);

namespace Dag\Generator\Impl;

use Nelmio\Alice\Loader\NativeLoader;
use stdClass;

final class AliceGenerator implements IGenerator
{

	/** @var NativeLoader */
	private $loader;

	public function __construct(NativeLoader $loader)
	{
		$this->loader = $loader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function generate($input): array
	{
		$parameters = [];
		$objects = [];

		// Phaze #1 (preparing)
		if (isset($input['objects'])) {
			$os1 = $this->loader->loadData($input['objects']);
			$objects = $os1->getObjects();
		}

		// Phaze #2 (generating)
		$os2 = $this->loader->loadData($input['schema'], $parameters, $objects);

		// Phaze #3 (normalizing)
		if (isset($input['normalize'])) {
			$os3 = $this->loader->loadData([
				stdClass::class => [
					'output' => [
						'field' => $input['normalize'],
					],
				],
			], $parameters, $os2->getObjects());

			return ((array) $os3->getObjects()['output'])['field'];
		}

		return $os2->getObjects();
	}

}
