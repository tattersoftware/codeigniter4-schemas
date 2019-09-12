<?php

use Tatter\Schemas\Handlers\BaseHandler;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;

class StructuresTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function testMagicMethods()
	{
		$schema = new Schema();
		$this->assertIsArray($schema->tables);
		$this->assertNull($schema->foo);
	}
}
