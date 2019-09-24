<?php

use Tatter\Schemas\Handlers\CacheHandler;
use Tatter\Schemas\Handlers\DatabaseHandler;
use Tatter\Schemas\Handlers\FileHandler;
use Tatter\Schemas\Handlers\ModelHandler;

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
	
	public function testDatabaseMergeFile()
	{
		$databaseHandler = new DatabaseHandler($this->config, 'tests');
		$fileHandler     = new FileHandler($this->config);
			
		$schema = $this->schemas->import([$databaseHandler, $fileHandler])->get();
		
		$this->assertObjectHasAttribute('products', $schema->tables);
		$this->assertCount(2, $schema->tables->workers->relations);
	}
	
	public function testMergeAllHandlers()
	{
		$databaseHandler = new DatabaseHandler($this->config, 'tests');
		$modelHandler    = new ModelHandler($this->config);
		$fileHandler     = new FileHandler($this->config);
			
		$schema = $this->schemas->import([$databaseHandler, $modelHandler, $fileHandler])->get();
		
		$this->assertObjectHasAttribute('products', $schema->tables);
		$this->assertEquals('CIModuleTests\Support\Models\FactoryModel', $schema->tables->factories->model);
		$this->assertCount(2, $schema->tables->workers->relations);
	}
}
