<?php

use Tatter\Schemas\Handlers\BaseHandler;
use Tatter\Schemas\Structures\Schema;

class SchemasTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		
		$config                        = new \Tatter\Schemas\Config\Schemas();
		$config->silent                = false;
		$config->ignoreMigrationsTable = true;
		$config->constrainByPrefix     = true;
		
		$this->config  = $config;
		$this->schemas = new \Tatter\Schemas\Schemas($config);
	}

	public function testGetConfig()
	{
		$this->assertEquals($this->config, $this->schemas->getConfig());
	}

	public function testStartsWithBlankSchema()
	{
		$schema = new Schema();
		$this->assertEquals($schema, $this->schemas->get());
	}
}
