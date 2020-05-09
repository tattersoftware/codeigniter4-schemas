<?php

use Tatter\Schemas\Drafter\Handlers\DirectoryHandler;

class DirectoryDrafterTest extends Tests\Support\UnitTestCase
{
	public function testEmptyDirectoryReturnsNull()
	{
		$config = $this->config;
		$config->schemasDirectory = SUPPORTPATH . 'Schemas/NoFiles';
		$handler = new DirectoryHandler($config);
		
		$this->assertNull($handler->draft());
	}

	public function testNoHandlersReturnsNull()
	{
		$config = $this->config;
		$config->schemasDirectory = SUPPORTPATH . 'Schemas/NoHandler';
		$handler = new DirectoryHandler($config);
		
		$this->assertNull($handler->draft());
	}

	public function testSuccessReturnsSchemaNoErrors()
	{
		$config = $this->config;
		$config->schemasDirectory = SUPPORTPATH . 'Schemas/Good';
		$handler = new DirectoryHandler($config);
		
		$schema = $handler->draft();

		$this->assertEquals('hasMany', $schema->tables->workers->relations->products->type);		
		$this->assertCount(0, $handler->getErrors());
	}
}
