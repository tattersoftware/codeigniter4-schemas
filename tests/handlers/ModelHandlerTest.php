<?php

use Tatter\Schemas\Handlers\ModelHandler;

class ModelHandlerTest extends CIModuleTests\Support\UnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->handler = new ModelHandler($this->config);
	}

	public function testGetSetGroup()
	{
		$this->assertEquals('tests', $this->handler->getGroup());
		$this->handler->setGroup('foobar');
		$this->assertEquals('foobar', $this->handler->getGroup());
	}

	public function testGetModels()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'getModels');
		$models = $method($this->handler);		

		$this->assertCount(4, $models);
		$this->assertContains('CIModuleTests\Support\Models\FactoryModel', $models);
	}

	public function testGetModelsRespectsGroup()
	{
		$this->handler->setGroup('default');
		
		$method = $this->getPrivateMethodInvoker($this->handler, 'getModels');
		$models = $method($this->handler);		

		$this->assertCount(0, $models);
	}
/*
	public function testExport()
	{		
		$this->assertTrue($this->handler->export($this->schema));
	}
*/

	public function testImport()
	{		
		$schema = $this->handler->get();
		dd($schema);
		$this->assertEquals($schema);
	}
}
