<?php namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use Tatter\Schemas\Config\Schemas as SchemasConfig;
use Tatter\Schemas\Schemas;
use Tatter\Schemas\Structures\Schema;

class SchemasTestCase extends CIUnitTestCase
{
	/**
	 * @var SchemasConfig
	 */
	protected $config;

	/**
	 * @var Schemas|null
	 */
	protected $schemas;

	/**
	 * @var Schema|null
	 */
	protected $schema;

	/**
	 * Creates a standard, stable testing config
	 * and initializes the library with it.
	 */
	public function setUp(): void
	{
		parent::setUp();
		
		$config                    = new SchemasConfig();
		$config->silent            = false;
		$config->ignoredTables     = ['migrations'];
		$config->ignoredNamespaces = ['Tatter\Agents'];
		$config->schemasDirectory  = SUPPORTPATH . 'Schemas';
		$config->automate = [
			'draft'   => false,
			'archive' => false,
			'read'    => false,
		];

		$this->config  = $config;
		$this->schemas = new Schemas($config);
	}
}
