<?php declare(strict_types = 1);

namespace Dag\Http;

use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Utils\Json;

final class DagResponse
{

	/** @var mixed */
	private $payload;

	/** @var mixed[] */
	private $headers = [];

	/** @var int */
	private $statusCode = 200;

	/** @var string */
	private $contentType = 'application/json';

	public function __construct($payload)
	{
		$this->payload = $payload;
	}

	public function setHeader(string $name, string $value): void
	{
		$this->headers[$name] = $value;
	}

	public function setStatusCode(int $statusCode): void
	{
		$this->statusCode = $statusCode;
	}

	public function setContentType(string $contentType): void
	{
		$this->contentType = $contentType;
	}

	public function send(Request $request, Response $response): void
	{
		foreach ($this->headers as $name => $value) {
			$response->setHeader($name, $value);
		}

		$response->setCode($this->statusCode);
		$response->setContentType($this->contentType, 'utf-8');

		echo Json::encode($this->payload);
	}

}
