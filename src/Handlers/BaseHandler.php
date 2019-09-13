<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;

class BaseHandler
{	
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Schemas\Config\Schema
	 */
	protected $config;
	
	/**
	 * Array of error messages assigned on failure.
	 *
	 * @var array
	 */
	protected $errors = [];
	
	// Initiate library
	public function __construct(BaseConfig $config = null)
	{
		// If no configuration was supplied then get one from the service
		if (is_null($config))
		{
			$schemas = \Config\Services::schemas();
			$config = $schemas->getConfig();
		}
		
		// Save the configuration
		$this->config = $config;

		// Load the inflector helper for singular <-> plural
		helper('inflector');
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
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
	 * Filler function for methods that aren't implemented (yet).
	 *
	 * @param string $class   The handler class
	 * @param string $method  The missing method
	 *
	 * @throws 
	 */
	protected function methodNotImplemented(string $class, string $method)
	{
		if (! $this->config->silent)
			throw SchemasException::forMethodNotImplemented($class, $method);

		$this->errors[] = lang('Schemas.methodNotImplemented', [$class, $method]);
	}
}
