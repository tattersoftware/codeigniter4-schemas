<?php

use Tatter\Schemas\Handlers\FileHandler;

class FileHandlerTest extends CIModuleTests\Support\UnitTestCase
{
	public function testEmptyDirectoryReturnsNull()
	{
		$config = $this->config;
		$config->schemasDirectory = SUPPORTPATH . 'Schemas/NoFiles';
		$handler = new FileHandler($config);
		
		$this->assertNull($handler->get());
	}

	public function testNoHandlersReturnsNull()
	{
		$config = $this->config;
		$config->schemasDirectory = SUPPORTPATH . 'Schemas/NoHandler';
		$handler = new FileHandler($config);
		
		$this->assertNull($handler->get());
	}

	public function testSuccessReturnsSchemaNoErrors()
	{
		$config = $this->config;
		$config->schemasDirectory = SUPPORTPATH . 'Schemas/Good';
		$handler = new FileHandler($config);
		
		$schema = $handler->get();

		$this->assertEquals('hasMany', $schema->tables->workers->relations->products->type);		
		$this->assertCount(0, $handler->getErrors());
	}
}
