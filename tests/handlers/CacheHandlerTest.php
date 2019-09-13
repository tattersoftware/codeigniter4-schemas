<?php

use Tatter\Schemas\Handlers\CacheHandler;

class CacheHandlerTest extends CIModuleTests\Support\UnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
				
		//$cache = new MockCache(); ??
		$this->handler = new CacheHandler($this->config);
	}

	public function testGetSetKey()
	{
		$this->assertEquals('schema', $this->handler->getKey());
		$this->handler->setKey('testKey');
		$this->assertEquals('testKey', $this->handler->getKey());
	}

	public function testSave()
	{		
		$this->assertTrue($this->handler->save($this->schema));
	}

	public function testGetSaveConsistency()
	{		
		$this->handler->save($this->schema);
		$this->assertEquals($this->schema, $this->handler->get());
	}
	
	public function tearDown(): void
	{
		parent::tearDown();
		$cache = $this->getPrivateProperty($this->handler, 'cache');
		$cache->clean();
	}
}
