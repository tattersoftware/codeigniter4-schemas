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

	public function testSaveNotImplemented()
	{
		$this->expectException('Tatter\Schemas\Exceptions\SchemasException', 'Tatter\Schemas\Handlers\ModelHandler does not have a save method');
		$this->handler->save($this->schema);
	}

	public function testGet()
	{		
		$schema = $this->handler->get();

		$this->assertEquals('servicers', $schema->tables->servicers->name);
		$this->assertEquals('CIModuleTests\Support\Models\WorkerModel', $schema->tables->workers->model);
		$this->assertCount(6, $schema->tables->machines->fields);
		$this->assertTrue($schema->tables->factories->fields->id->primary_key);
	}
}
