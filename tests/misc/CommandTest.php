<?php

use Config\Services;
use Tatter\Schemas\Commands\Schemas as SchemasCommand;
use Tests\Support\SchemasTestCase;

/**
 * @internal
 */
final class CommandTest extends SchemasTestCase
{
	public function testGetHandlerReturnsClass()
	{
		$command = new SchemasCommand(Services::logger(), Services::commands());
		$method  = $this->getPrivateMethodInvoker($command, 'getHandler');

		$handler = $method('Drafter', 'database');
		$this->assertSame('\Tatter\Schemas\Drafter\Handlers\DatabaseHandler', $handler);

		$handler = $method('Archiver', 'cache');
		$this->assertSame('\Tatter\Schemas\Archiver\Handlers\CacheHandler', $handler);
	}
}
