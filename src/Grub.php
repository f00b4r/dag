<?php declare(strict_types = 1);

namespace Dag;

use Dag\Utils\Zeit;
use RuntimeException;

class Grub
{

	public static function boot(): void
	{
		$grub = new static();
		$dag = new Dag($grub->getParameters());

		// Error tracking
		$dag->trace();

		// Route request & serve response
		$dag->serve();
	}

	protected function getParameters(): array
	{
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$last = end($trace);
		$dir = isset($last['file']) ? dirname($last['file']) : null;

		if (!$dir) {
			throw new RuntimeException('Cannot detect caller file');
		}

		return [
			'config' => $dir . '/dag.neon',
			'tempDir' => Zeit::now() ? '/tmp' : $dir . '/../var',
			'logDir' => Zeit::now() ? '/tmp' : $dir . '/../var',
			'debug' => getenv('DEBUG') === '1',
		];
	}
}
