<?php

use Tatter\Schemas\Handlers\BaseHandler;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;

class SchemasTest extends CIModuleTests\Support\UnitTestCase
{
	public function testGetConfig()
	{
		$this->assertEquals($this->config, $this->schemas->getConfig());
	}

	public function testStartsWithoutSchema()
	{
		$this->assertNull($this->schemas->get());
	}

	public function testGetHandlerFromClass()
	{
		$method = $this->getPrivateMethodInvoker($this->schemas, 'getHandlerFromClass');
		
		$handler = $method('database');
		$this->assertInstanceOf(SchemaHandlerInterface::class, $handler);

		$handler = $method('cache');
		$this->assertInstanceOf(SchemaHandlerInterface::class, $handler);
	}
}
