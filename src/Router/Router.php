<?php declare(strict_types = 1);

namespace Dag\Router;

use Nette\Http\Request;
use Nette\Routing\RouteList;

class Router
{

	/** @var mixed[] */
	private $endpoints = [];

	/** @var RouteList */
	private $router;

	public function __construct()
	{
		$this->router = new RouteList();
	}

	public function add(string $path, array $endpoint): void
	{
		$this->endpoints[$path] = $endpoint;
		$this->router->addRoute($path, ['path' => $path]);
	}

	public function match(Request $request): ?array
	{
		$endpoint = $this->router->match($request);

		if ($endpoint === null) return null;

		return $this->endpoints[$endpoint['path']];
	}

}
