<?php

use Tatter\Schemas\Handlers\DatabaseHandler;

class DatabaseTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testHasAllTables()
	{
		$this->assertEquals(7, count($this->schema->tables));
	}

	public function testHasSpecificTable()
	{
		$this->assertArrayHasKey('factories', $this->schema->tables);
	}

	public function testHasSpecificTableWithPrefix()
	{
		$config = new \Tatter\Schemas\Config\Schemas();
		$config->constrainByPrefix = false;

		$handler = new DatabaseHandler($config, 'tests');
		$schema = $this->schemas->from($handler)->get();
		$DBPrefix = $this->getPrivateProperty($handler, 'prefix');
		
		$this->assertArrayHasKey($DBPrefix . 'factories', $schema->tables);
	}

	public function testDetectsPivotTablesWithFK()
	{
		$this->assertTrue($this->schema->tables['factories_workers']->pivot);
	}

	public function testDetectsPivotTablesWithoutFK()
	{
		$this->assertTrue($this->schema->tables['machines_servicers']->pivot);
	}

	public function testIgnoreMigrationsTable()
	{
		$this->assertArrayNotHasKey('migrations', $this->schema->tables);

		$config = new \Tatter\Schemas\Config\Schemas();
		$config->ignoreMigrationsTable = false;

		$handler = new DatabaseHandler($config, 'tests');
		$schema = $this->schemas->from($handler)->get();
		
		$this->assertArrayHasKey('migrations', $schema->tables);
	}
}
