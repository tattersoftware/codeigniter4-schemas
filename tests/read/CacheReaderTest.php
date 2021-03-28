<?php

use Tatter\Schemas\Reader\Handlers\CacheHandler as CacheReader;
use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Schema;
use Tests\Support\CacheTrait;
use Tests\Support\MockSchemaTrait;
use Tests\Support\SchemasTestCase;

class CacheReaderTest extends SchemasTestCase
{
	use CacheTrait, MockSchemaTrait;

	/**
	 * @var CacheReader
	 */
	private $reader;

	public function setUp(): void
	{
		parent::setUp();

		// Archive a copy of the schema so we can test reading it
		$schema = clone $this->schema;
		$this->archiver->archive($schema);

		// Initializing the Reader also accesses the Cache, so do it last
		$this->reader = new CacheReader($this->config, $this->cache);
	}

	public function testReaderHasScaffold()
	{
		$expected = [
			'factories' => true,
			'machines'  => true,
			'workers'   => true,
		];

		$this->assertEquals($expected, (array) $this->reader->getTables());
	}

	public function testReaderMagicGetsTable()
	{
		$table = $this->reader->workers;

		$expected = [
			'factories' => true,
			'machines'  => true,
			'workers'   => $this->schema->tables->workers,
		];

		$this->assertEquals($expected, (array)$this->reader->getTables());
	}

	public function testReaderIteratesAllTables()
	{
		$counted = 0;

		foreach ($this->reader as $tableName => $table)
		{
			$this->assertEquals($table, $this->schema->tables->$tableName);
			$counted++;
		}

		$this->assertEquals(3, $counted);
	}
}
