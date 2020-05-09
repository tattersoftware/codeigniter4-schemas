<?php

use Tatter\Schemas\Drafter\Handlers\ModelHandler;

class ModelDrafterTest extends Tests\Support\UnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->handler = new ModelHandler($this->config);
	}

	public function testSetGroup()
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
		$this->assertContains('Tests\Support\Models\FactoryModel', $models);
	}

	public function testGetModelsRespectsGroup()
	{
		$this->handler->setGroup('default');
		
		$method = $this->getPrivateMethodInvoker($this->handler, 'getModels');
		$models = $method($this->handler);		

		$this->assertCount(0, $models);
	}

	public function testDraftsSchemaFromModels()
	{		
		$schema = $this->handler->draft();

		$this->assertEquals('servicers', $schema->tables->servicers->name);
		$this->assertEquals('Tests\Support\Models\WorkerModel', $schema->tables->workers->model);
		$this->assertCount(6, $schema->tables->machines->fields);
		$this->assertTrue($schema->tables->factories->fields->id->primary_key);
	}
}
