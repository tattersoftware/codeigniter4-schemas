<?php

use Tatter\Schemas\Handlers\CacheHandler;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\ForeignKey;

class CacheHandlerTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		
		$config                        = new \Tatter\Schemas\Config\Schemas();
		$config->silent                = false;
		$config->ignoreMigrationsTable = true;
		$config->constrainByPrefix     = true;
		$this->config  = $config;
		
		//$cache = new MockCache(); ??
		$this->handler = new CacheHandler($config);
		$this->schemas = new \Tatter\Schemas\Schemas($config);
		
		// Use a mock schema so we don't have to hit the database
		$relation = new Relation;
		$relation->type   = 'manyToMany';
		$relation->table  = 'workers';
		$relation->pivots = [
			['factories_workers', 'id', 'factory_id'],
			['workers', 'worker_id', 'id'],
		];
		
		$table1 = new Table('factories');
		$table1->fields = [
			'id'   => new Field('id'),
			'name' => new Field('name'),
			'uid'  => new Field('uid'),
		];
		$table1->relations = ['workers' => $relation];
		
		$relation = new Relation;
		$relation->type   = 'manyToMany';
		$relation->table  = 'factories';
		$relation->pivots = [
			['factories_workers', 'id', 'worker_id'],
			['factories', 'factory_id', 'id'],
		];
		
		$table2 = new Table('workers');
		$table2->fields = [
			'id'        => new Field('id'),
			'firstname' => new Field('firstname'),
			'lastname'  => new Field('lastname'),
			'role'      => new Field('role'),
		];
		$table2->relations = ['factories' => $relation];
		
		$this->schema = new Schema();
		$this->schema->tables = [
			'factories' => $table1,
			'workers'   => $table2,
		];
	}

	public function testGetSetKey()
	{
		$this->assertEquals('schema', $this->handler->getKey());
		$this->handler->setKey('testKey');
		$this->assertEquals('testKey', $this->handler->getKey());
	}

	public function testExport()
	{		
		$this->assertTrue($this->handler->export($this->schema));
	}

	public function testImport()
	{		
		$this->handler->export($this->schema);
		$this->assertEquals($this->schema, $this->handler->import());
	}
	
	public function tearDown(): void
	{
		parent::tearDown();
		unset($this->schema);

		$cache = $this->getPrivateProperty($this->handler, 'cache');
		$cache->clean();
	}
}
