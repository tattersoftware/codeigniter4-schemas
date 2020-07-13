<?php

use Tatter\Schemas\Archiver\Handlers\CacheHandler;
use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Schema;

class CacheArchiverTest extends Tests\Support\UnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
				
//		$this->cache = new MockCache(); ??
		$this->cache = \Config\Services::cache();
		
		$this->handler = new CacheHandler($this->config, $this->cache);
	}

	public function testGetKeyUsesEnvironment()
	{
		$this->assertEquals('schema-testing', $this->handler->getKey());
	}

	public function testSetKeyChangesKey()
	{
		$this->handler->setKey('testKey');

		$this->assertEquals('testKey', $this->handler->getKey());
	}

	public function testArchiveReturnsTrueOnSuccess()
	{		
		$this->assertTrue($this->handler->archive($this->schema));
	}

	public function testArchiveStoresScaffold()
	{
		$key = $this->handler->getKey();
		$this->handler->archive($this->schema);
		
		$expected = new Schema();
		$expected->tables = new Mergeable();

		foreach ($this->schema->tables as $tableName => $table)
		{
			$expected->tables->$tableName = true;
		}
		
		$this->assertEquals($this->schema, $this->cache->get($key));
	}

	public function testArchiveStoresEachTable()
	{
		$key = $this->handler->getKey();
		$tables = $this->schema->tables;

		$this->handler->archive($this->schema);

		foreach ($tables as $tableName => $table)
		{
			$this->assertEquals($table, $this->cache->get($key . '-' . $tableName));
		}
	}
	
	public function tearDown(): void
	{
		parent::tearDown();

		$this->cache->clean();

		$cache = $this->getPrivateProperty($this->handler, 'cache');
		$cache->clean();
	}
}
