<?php

use Tatter\Schemas\Handlers\CacheHandler;
use Tatter\Schemas\Handlers\DatabaseHandler;

class LiveTest extends CIModuleTests\Support\DatabaseTestCase
{
	// Probably the most likely scenario for use
	public function testDatabaseToCache()
	{
		$cache           = \Config\Services::cache();
		$cacheHandler    = new CacheHandler($this->config, $cache);
		$databaseHandler = new DatabaseHandler($this->config, 'tests');
		
		$this->schemas->import($databaseHandler)->export($cacheHandler);
		$this->assertEmpty($this->schemas->getErrors());
		
		$schemaFromService = $this->schemas->get();
		$schemaFromCache   = $cache->get('schema');
		$this->assertEquals($schemaFromCache, $schemaFromService);
		
		$this->assertObjectHasAttribute('factories', $schemaFromCache->tables);
	}
}
