<?php

use Tatter\Schemas\Handlers\BaseHandler;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;

class SchemasTest extends CIModuleTests\Support\UnitTestCase
{
	public function testGetErrors()
	{
		$this->assertEquals([], $this->schemas->getErrors());
	}

	public function testStartsWithEmptySchema()
	{
		$schema = new Schema();
		
		$this->assertEquals($schema, $this->schemas->get());
	}

	public function testStartsWithoutSchema()
	{
		$this->assertNull($this->schemas->get());
	}

	public function testGetHandlerFromClass()
	{
		$command = new \Tatter\Schemas\Commands\Schemas()_;
		$method = $this->getPrivateMethodInvoker($command, 'getHandlerFromClass');
		
		$handler = $method('Drafter', 'database');
		$this->assertInstanceOf(BaseHandler::class, $handler);

		$handler = $method('Archiver', 'cache');
		$this->assertInstanceOf(BaseHandler::class, $handler);
	}
}
