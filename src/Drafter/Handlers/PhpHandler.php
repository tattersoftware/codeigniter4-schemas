<?php namespace Tatter\Schemas\Drafter\Handlers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Drafter\BaseDrafter;
use Tatter\Schemas\Drafter\DrafterInterface;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Structures\Schema;

class PhpHandler extends BaseDrafter implements DrafterInterface
{
	/**
	 * The path to the file.
	 *
	 * @var string
	 */
	protected $path;
	
	/**
	 * Save the config and the path to the file
	 *
	 * @param BaseConfig  $config   The library config
	 * @param string      $path     Path to the file to process
	 */
	public function __construct(BaseConfig $config = null, $path = null)
	{
		parent::__construct($config);
		
		// Save the path
		$this->path = $path;
	}

	/**
	 * Read in data from the file and fit it into a schema
	 *
	 * @return Schema|null
	 */
	public function draft(): ?Schema
	{
		$contents = $this->getContents($this->path);
		if (is_null($contents))
		{
			$this->errors[] = lang('Schemas.emptySchemaFile', [$this->path]);
			return null;
		}
		
		// PHP files should contain pre-built schemas in the $schema variable
		// So the path just needs to be included and the variable checked
		try {
			require $this->path;
		}
		catch (\Exception $e) {
			$this->errors[] = $e->getMessage();
			return null;
		}

		return $schema ?? null;
	}
}
