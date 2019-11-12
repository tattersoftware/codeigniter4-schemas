<?php

use Tatter\Schemas\Drafter\Handlers\CacheHandler;
use Tatter\Schemas\Drafter\Handlers\DatabaseHandler;
use Tatter\Schemas\Drafter\Handlers\DirectoryHandler;
use Tatter\Schemas\Drafter\Handlers\ModelHandler;

class LiveTest extends CIModuleTests\Support\DatabaseTestCase
{
	// Probably a quite common scenario
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
		$fileHandler     = new DirectoryHandler($this->config);
			
		$schema = $this->schemas->import([$databaseHandler, $fileHandler])->get();
		
		$this->assertObjectHasAttribute('products', $schema->tables);
		$this->assertCount(3, $schema->tables->workers->relations);
	}
	
	public function testMergeAllHandlers()
	{
		$databaseHandler = new DatabaseHandler($this->config, 'tests');
		$modelHandler    = new ModelHandler($this->config);
		$fileHandler     = new DirectoryHandler($this->config);
			
		$schema = $this->schemas->import([$databaseHandler, $modelHandler, $fileHandler])->get();
		
		$this->assertObjectHasAttribute('products', $schema->tables);
		$this->assertEquals('CIModuleTests\Support\Models\FactoryModel', $schema->tables->factories->model);
		$this->assertCount(3, $schema->tables->workers->relations);
	}
}
