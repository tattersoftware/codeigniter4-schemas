<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;

class BaseHandler
{
	// Trait the Reflection helper to get table names from models
	use \CodeIgniter\Test\ReflectionHelper;
	
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Schemas\Config\Schema
	 */
	protected $config;
	
	/**
	 * Array of error messages assigned on failure
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
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
	}
	
	/**
	 * Use the ReflectionHelper trait to get the protected "table" property 
	 *
	 * @param mixed    $model  A model instance or class name
	 *
	 * @return string  The name of the table for this model
	 */
	public function getModelTable($model): string
	{
		if (is_string($model))
		{
			$model = new $model();
		}
		
		return $this->getPrivateProperty($model, $table);
	}
}
