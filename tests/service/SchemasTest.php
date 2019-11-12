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
/*
	public function testGetHandlerFromClass()
	{
		$method = $this->getPrivateMethodInvoker($this->schemas, 'getHandlerFromClass');
		
		$handler = $method('database');
		$this->assertInstanceOf(SchemaHandlerInterface::class, $handler);

		$handler = $method('cache');
		$this->assertInstanceOf(SchemaHandlerInterface::class, $handler);
	}
*/
}
