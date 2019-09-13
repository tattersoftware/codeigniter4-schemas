<?php

use Tatter\Schemas\Handlers\CacheHandler;
use Tatter\Schemas\Handlers\DatabaseHandler;

class SchemasLiveTest extends CIModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	// Probably the most likely scenario for use
	public function testDatabaseToCache()
	{
		$cache           = \Config\Services::cache();
		$cacheHandler    = new CacheHandler($this->config, $cache);
		$databaseHandler = new DatabaseHandler($this->config, 'tests');
		
		$this->schemas->import($databaseHandler)->export($cacheHandler);
		$this->assertEmpty($this->schemas->getErrors());
		
		$schemaFromLibrary = $this->schemas->get();
		$schemaFromCache   = $cache->get('schema');
		$this->assertEquals($schemaFromCache, $schemaFromLibrary);
		
		$this->assertObjectHasAttribute('factories', $schemaFromCache->tables);
	}
}
