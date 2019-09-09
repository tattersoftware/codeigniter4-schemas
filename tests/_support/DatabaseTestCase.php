<?php namespace CIModuleTests\Support;

use CodeIgniter\Config\Services;

class DatabaseTestCase extends \CodeIgniter\Test\CIDatabaseTestCase
{
	/**
	 * Should the database be refreshed before each test?
	 *
	 * @var boolean
	 */
	protected $refresh = true;

	/**
	 * The name of a seed file used for all tests within this test case.
	 *
	 * @var string
	 */
	protected $seed = 'CIModuleTests\Support\Database\Seeds\TestSeeder';

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
	protected $namespace = 'CIModuleTests\Support';

	/**
	 * Instance of the library.
	 */
	protected $schemas;

	public function setUp(): void
	{
		parent::setUp();
		
		$config         = new \Tatter\Schemas\Config\Schemas();
		$config->silent = false;
		$this->schemas = new \Tatter\Schemas\Schemas($config);
	}
}
