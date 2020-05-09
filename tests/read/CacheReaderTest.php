<?php

use Tatter\Schemas\Reader\Handlers\CacheHandler;
use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Schema;

class CacheReaderTest extends Tests\Support\UnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
				
//		$this->cache = new MockCache(); ??
		$this->cache = \Config\Services::cache();
		
		// Archive a copy of the schema so we can test reading it
		$schema = clone $this->schema;
		$archiver = new \Tatter\Schemas\Archiver\Handlers\CacheHandler($this->config, $this->cache);
		$archiver->archive($schema);
		unset($archiver);
		unset($schema);
		
		$this->reader = new CacheHandler($this->config, $this->cache);
	}

	public function testReaderHasScaffold()
	{
		$expected = [
			'factories' => true,
			'workers'   => true,
		];
		
		$this->assertEquals($expected, (array)$this->reader->getTables());
	}

	public function testReaderMagicGetsTable()
	{
		$table = $this->reader->workers;

		$expected = [
			'factories' => true,
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
		
		$this->assertEquals(2, $counted);
	}
	
	public function tearDown(): void
	{
		parent::tearDown();

		$this->cache->clean();

		$cache = $this->getPrivateProperty($this->reader, 'cache');
		$cache->clean();
	}
}
