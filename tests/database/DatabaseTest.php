<?php

class DatabaseTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testSchemaHasAllTables()
	{
		$schema = $this->schemas->from($this->handler)->get();
		
		$this->assertEquals(8, count($schema->tables));
	}

	public function testDatabaseHandler()
	{
		dd($this->schema);
	}
}
