<?php namespace Tatter\Schemas;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\ForeignKey;

class Schemas
{
	/**
	 * The current config.
	 *
	 * @var Tatter\Schemas\Config\Schemas
	 */
	protected $config;

	/**
	 * The current schema.
	 *
	 * @var Tatter\Schemas\Structures\Schema
	 */
	protected $schema;
	
	/**
	 * Array of error messages assigned on failure.
	 *
	 * @var array
	 */
	protected $errors = [];
	
	// Initiate library
	public function __construct(BaseConfig $config, Schema $schema = null)
	{
		$this->config = $config;
		
		// Store starting schema
		if (! is_null($schema))
		{
			$this->schema = $schema;
		}
	}
	
	// Return a copy of the config - usually used by handlers
	public function getConfig(): BaseConfig
	{
		return $this->config;
	}
	
	// Return the schema
	public function get(): ?Schema
	{
		return $this->schema;
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
	}
	
	/**
	 * Uses the provided handlers or handler names to import a new schema
	 *
	 * @return $this
	 */
	public function import(...$handlers)
	{
		// If no schema is loaded then start a fresh one
		if (is_null($this->schema))
		{
			$this->schema = new Schema();
		}
		
		// Import from each handler in order
		foreach ($handlers as $handler)
		{
			// Check for a handler name
			if (is_string($handler))
			{
				$handler = $this->getHandlerFromClass($handler);
			}

			$this->schema->merge($handler->import());
			$this->errors = array_merge($this->errors, $handler->getErrors());
		}

		return $this;
	}
	
	/**
	 * Uses the provided handler or handler name to export the current schema
	 *
	 * @return $this
	 */
	public function export($handler)
	{
		// Check for a handler name
		if (is_string($handler))
		{
			$handler = $this->getHandlerFromClass($handler);
		}

		$handler->export($this->schema);
		$this->errors = array_merge($this->errors, $handler->getErrors());
		return $this;
	}
	
	/**
	 * Tries to match a class name or shortname to its handler
	 *
	 * @return SchemaHandlerInterface
	 */	
	protected function getHandlerFromClass(string $class): SchemaHandlerInterface
	{		
		// Check if its already namespaced
		if (strpos($class, '\\') === false)
		{
			$class = '\Tatter\Schemas\Handlers\\' . ucfirst($class) . 'Handler';
		}

		if (! class_exists($class))
		{
			throw SchemasException::forUnsupportedHandler($class);
		}
		
		$handler = new $class($this->config);
		return $handler;
	}
}
