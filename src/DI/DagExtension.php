<?php declare(strict_types = 1);

namespace Dag\DI;

use Dag\Generator\Generator;
use Dag\Generator\Impl\AliceGenerator;
use Dag\Generator\Impl\ExceptionGenerator;
use Dag\Generator\Impl\FallbackGenerator;
use Dag\Generator\Impl\IGenerator;
use Dag\Handler\Executor;
use Dag\Router\Router;
use Dag\Utils\Arrays;
use Nelmio\Alice\Loader\NativeLoader;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\Http\Request;
use Nette\Http\RequestFactory;
use Nette\Http\Response;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class DagExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'endpoints' => Expect::arrayOf(
				Expect::structure([
					'generator' => Expect::string(),
					'response' => Expect::structure([
						'headers' => Expect::array(),
					]),
					'data' => Expect::structure([
						'normalize' => Expect::string(),
						'objects' => Expect::array(),
						'schema' => Expect::array(),
					]),
				])
			),
		]);
	}

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = (array) $this->config;

		$builder->addDefinition($this->prefix('http.request'))
			->setType(Request::class)
			->setFactory(new Statement([
				new Statement(RequestFactory::class),
				'fromGlobals',
			]));

		$builder->addDefinition($this->prefix('http.response'))
			->setFactory(Response::class);

		$builder->addDefinition($this->prefix('generator.alice'))
			->setFactory(AliceGenerator::class, [
				new Statement(NativeLoader::class),
			]);

		$builder->addDefinition($this->prefix('generator.fallback'))
			->setFactory(FallbackGenerator::class);

		$builder->addDefinition($this->prefix('generator.exception'))
			->setFactory(ExceptionGenerator::class);

		$builder->addDefinition($this->prefix('generator'))
			->setFactory(Generator::class)
			->addSetup('add', ['alice', $this->prefix('@generator.alice')])
			->addSetup('add', [IGenerator::FALLBACK, $this->prefix('@generator.fallback')])
			->addSetup('add', [IGenerator::EXCEPTION, $this->prefix('@generator.exception')]);

		$builder->addDefinition($this->prefix('endpoint.executor'))
			->setFactory(Executor::class);

		$router = $builder->addDefinition($this->prefix('router'))
			->setFactory(Router::class);

		foreach ($config['endpoints'] as $path => $endpoint) {
			$router->addSetup('add', [$path, Arrays::toArray($endpoint)]);
		}
	}

}
