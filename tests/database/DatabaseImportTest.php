<?php

use Tatter\Schemas\Handlers\DatabaseHandler;

class DatabaseImportTest extends CIModuleTests\Support\DatabaseTestCase
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
		$this->assertObjectHasAttribute('factories', $this->schema->tables);
	}

	public function testDetectsPivotTablesWithFK()
	{
		$this->assertTrue($this->schema->tables->factories_workers->pivot);
	}

	public function testDetectsPivotTablesWithoutFK()
	{
		$this->assertTrue($this->schema->tables->machines_servicers->pivot);
	}

	public function testIgnoreMigrationsTable()
	{
		$this->assertObjectNotHasAttribute('migrations', $this->schema->tables);

		$config = new \Tatter\Schemas\Config\Schemas();
		$config->ignoreMigrationsTable = false;

		$handler = new DatabaseHandler($config, 'tests');
		$schema = $this->schemas->import($handler)->get();
		
		$this->assertObjectHasAttribute('migrations', $schema->tables);
	}

	// -------------------- RELATIONSHIPS --------------------
	
	public function testDetectsAllRelationships()
	{
		$relationsCount = 0;
		foreach ($this->schema->tables as $table)
		{
			$relationsCount += count($table->relations);
		}
		
		$this->assertEquals(8, $relationsCount);
	}
	
	public function testBelongsTo()
	{
		$table1 = $this->schema->tables->lawyers;
		$table2 = $this->schema->tables->servicers;
		
		$this->assertEquals('belongsTo', $table1->relations->{$table2->name}->type);
		
		$pivot = ['servicers', 'servicer_id', 'id'];
		$this->assertEquals([$pivot], $table1->relations->{$table2->name}->pivots);
	}
	
	public function testHasMany()
	{
		$table1 = $this->schema->tables->servicers;
		$table2 = $this->schema->tables->lawyers;
		
		$this->assertEquals('hasMany', $table1->relations->{$table2->name}->type);
		
		$pivot = ['lawyers', 'id', 'servicer_id'];
		$this->assertEquals([$pivot], $table1->relations->{$table2->name}->pivots);
	}
	
	public function testManyToMany()
	{
		$table1 = $this->schema->tables->servicers;
		$table2 = $this->schema->tables->machines;
		
		$this->assertEquals('manyToMany', $table1->relations->{$table2->name}->type);
		$this->assertEquals('manyToMany', $table2->relations->{$table1->name}->type);
		
		$pivot1 = ['machines_servicers', 'id', 'servicer_id'];
		$pivot2 = ['machines', 'machine_id', 'id'];
		$this->assertEquals([$pivot1, $pivot2], $table1->relations->{$table2->name}->pivots);
		
		$pivot1 = ['machines_servicers', 'id', 'machine_id'];
		$pivot2 = ['servicers', 'servicer_id', 'id'];
		$this->assertEquals([$pivot1, $pivot2], $table2->relations->{$table1->name}->pivots);
	}
}
