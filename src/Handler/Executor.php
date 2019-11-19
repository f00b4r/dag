<?php declare(strict_types = 1);

namespace Dag\Handler;

use Dag\Generator\Generator;
use Dag\Generator\Impl\IGenerator;
use Dag\Http\DagResponse;
use Dag\Router\Router;
use Nette\Http\Request;
use Throwable;

class Executor
{

	/** @var Router */
	private $router;

	/** @var Generator */
	private $generator;

	public function __construct(Router $router, Generator $generator)
	{
		$this->router = $router;
		$this->generator = $generator;
	}

	public function execute(Request $request): DagResponse
	{
		// Lookup for endpoint
		$endpoint = $this->router->match($request);

		// Fallback > no endpoint found
		if ($endpoint === null) {
			$generated = $this->generator->generate(IGenerator::FALLBACK, null);

			$respose = new DagResponse($generated);
			$respose->setStatusCode(404);

			return $respose;
		}

		// Generate content
		try {
			$generated = $this->generator->generate(
				$endpoint['generator'],
				$endpoint['data']
			);

			return new DagResponse($generated);
		} catch (Throwable $e) {
			// Fallback > exception occurred
			$generated = $this->generator->generate(IGenerator::EXCEPTION, $e);

			$respose = new DagResponse($generated);
			$respose->setStatusCode(500);

			return $respose;
		}
	}

}
