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
		$cache = $this->getPrivateProperty($this->handler, 'cache');
		$cache->clean();
	}
}
