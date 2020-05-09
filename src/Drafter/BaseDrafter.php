<?php namespace Tatter\Schemas\Drafter;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\Schemas\BaseHandler;
use Tatter\Schemas\Structures\Table;

class BaseDrafter extends BaseHandler
{
	/**
	 * Load the helper
	 *
	 * @param BaseConfig  $config   The library config
	 */
	public function __construct(BaseConfig $config = null)
	{
		parent::__construct($config);

		// Load the inflector helper for singular <-> plural
		if (! function_exists('singular'))
		{
			helper('inflector');
		}
	}

	/**
	 * Search a table for its primary key.
	 *
	 * @param Table    $table      A Table
	 *
	 * @return ?string The name of the field, or null if not found
	 */
	protected function findPrimaryKey(Table $table): ?string
	{
		foreach ($table->fields as $field)
		{
			if ($field->primary_key)
			{
				return $field->name;
			}
		}
		
		// Hail Mary for `id`
		if (isset($table->fields->id))
		{
			return 'id';
		}
		
		return null;
	}
	
	/**
	 * Search a table for fields that may be foreign keys to tableName.
	 *
	 * @param Table    $table      A Table
	 * @param string   $tableName  The foreign table to try to match
	 *
	 * @return ?String The name of the field, or null if not found
	 */
	protected function findKeyToForeignTable(Table $table, string $tableName): ?string
	{
		// Check a few common conventions
		$tests = [
			$tableName,
			$tableName . '_id',
			singular($tableName),
			singular($tableName) . '_id',
		];

		foreach ($tests as $fieldName)
		{
			if (isset($table->fields->$fieldName))
			{
				return $fieldName;
			}
		}

		return null;
	}

	// COMMON FUNCTIONS FOR FILE HANDLERS
	
	/**
	 * Validate a file and get its contents.
	 *
	 * @param string $path    The path to the file
	 *
	 * @throws 
	 */
	protected function getContents($path): ?string
	{
		$file = new File($path, $this->config->silent); // if not silent then will throw for missing files
		
		if (! $file->isFile())
		{
			$this->errors[] = lang('Files.fileNotFound', [$path]);
			return null;
		}
		
		return file_get_contents($file->getRealPath());
	}
}
