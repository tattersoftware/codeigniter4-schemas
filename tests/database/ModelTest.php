<?php

class ModelTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testModel()
	{
		$model = new \CIModuleTests\Support\Models\FactoryModel();

		$factories = $model->findAll();
		$this->assertCount(3, $factories);
	}
}
