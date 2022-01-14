<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
abstract class DatabaseTestCase extends CIUnitTestCase
{
	use DatabaseTestTrait;

	/**
	 * Should the database be refreshed before each test?
	 *
	 * @var bool
	 */
	protected $refresh = true;

	/**
	 * The name of a seed file used for all tests within this test case.
	 *
	 * @var string
	 */
	protected $seed = 'Tests\Support\Database\Seeds\TestSeeder';

	/**
	 * The path to where we can find the test Seeds directory.
	 *
	 * @var string
	 */
	protected $basePath = SUPPORTPATH . 'Database/';

	/**
	 * The namespace to help us find the migration classes.
	 *
	 * @var string
	 */
	protected $namespace = 'Tests\Support';

	/**
	 * Preconfigured config instance.
	 */
	protected $config;

	/**
	 * Instance of the library.
	 */
	protected $schemas;

	/**
	 * SchemaDatabaseHandler preloaded for 'tests' DBGroup.
	 */
	protected $handler;

	/**
	 * An initial schema generated from the 'tests' database.
	 */
	protected $schema;

	protected function setUp(): void
	{
		parent::setUp();

		$config                    = new \Tatter\Schemas\Config\Schemas();
		$config->silent            = false;
		$config->ignoredTables     = ['migrations'];
		$config->ignoredNamespaces = [];
		$config->schemasDirectory  = SUPPORTPATH . 'Schemas/Good';
		$config->automate          = [
			'draft'   => false,
			'archive' => false,
			'read'    => false,
		];

		$this->config  = $config;
		$this->schemas = new \Tatter\Schemas\Schemas($config);
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		$this->schemas->reset();
		unset($this->schema, $this->handler);

		cache()->clean();
	}
}
