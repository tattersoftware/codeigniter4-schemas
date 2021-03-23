<?php

use COnfig\Services;
use Tatter\Schemas\Commands\Schemas as SchemasCommand;
use Tests\Support\SchemasTestCase;

class CommandTest extends SchemasTestCase
{
	public function testGetHandlerReturnsClass()
	{
		$command = new SchemasCommand(Services::logger(), Services::commands());
		$method  = $this->getPrivateMethodInvoker($command, 'getHandler');
		
		$handler = $method('Drafter', 'database');
		$this->assertEquals('\Tatter\Schemas\Drafter\Handlers\DatabaseHandler', $handler);

		$handler = $method('Archiver', 'cache');
		$this->assertEquals('\Tatter\Schemas\Archiver\Handlers\CacheHandler', $handler);
	}
}
