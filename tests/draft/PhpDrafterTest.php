<?php

use Tatter\Schemas\Drafter\Handlers\PhpHandler;

class PhpDrafterTest extends Tests\Support\UnitTestCase
{
	public function testSuccessReturnsSchemaFromFile()
	{
		$path    = SUPPORTPATH . 'Schemas/Good/Products.php';
		$handler = new PhpHandler($this->config, $path);
		$schema  = $handler->draft();
		
		$this->assertEquals('hasMany', $schema->tables->workers->relations->products->type);		
		$this->assertCount(0, $handler->getErrors());
	}
	
	public function testEmptyFileReturnsNull()
	{
		$path = SUPPORTPATH . 'Schemas/Empty/NothingToSee.php';
		$handler = new PhpHandler($this->config, $path);
		
		$this->assertNull($handler->draft());
	}
	
	public function testMissingVariableReturnsNull()
	{
		$path = SUPPORTPATH . 'Schemas/Invalid/NoSchemaVariable.php';
		$handler = new PhpHandler($this->config, $path);
		
		$this->assertNull($handler->draft());
	}
}
