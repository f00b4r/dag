<?php declare(strict_types = 1);

namespace Dag;

use Dag\DI\DagExtension;
use Dag\Handler\Executor;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\Http\Request;
use Nette\Http\Response;
use Tracy\Debugger;

class Dag
{

	/** @var mixed[] */
	private $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function trace()
	{
		// Error tracking
		Debugger::$strictMode = true;
		Debugger::enable($this->config['debug'] ? Debugger::DEBUG : Debugger::DETECT, $this->config['logDir']);
	}

	public function serve()
	{
		$container = $this->createContainer();

		$httpRequest = $container->getByType(Request::class);
		$httpResponse = $container->getByType(Response::class);
		$executor = $container->getByType(Executor::class);

		$response = $executor->execute($httpRequest);

		$response->setHeader('Access-Control-Allow-Origin', '*');
		$response->setHeader('Access-Control-Allow-Methods', '*');
		$response->setHeader('Access-Control-Allow-Headers', '*');

		$response->send($httpRequest, $httpResponse);
	}

	private function createContainer(): Container
	{
		$class = $this->loadContainer();
		$container = new $class();
		$container->initialize();
		return $container;
	}

	private function loadContainer(): string
	{
		$loader = new ContainerLoader(
			$this->config['tempDir'] . '/container',
			$this->config['debug']
		);
		$class = $loader->load(
			function (Compiler $compiler) {
				$compiler->addExtension('dag', new DagExtension());
				$compiler->loadConfig($this->config['config']);
			},
			[$this->config, PHP_VERSION_ID - PHP_RELEASE_VERSION]
		);

		return $class;
	}

}
