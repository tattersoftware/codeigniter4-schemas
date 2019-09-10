<?php namespace Tatter\Schemas;

use CodeIgniter\Config\BaseConfig;
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
		
		// If not starting schema provided then use a blank one
		if (is_null($schema))
		{
			$this->schema = new Schema();
		}
		else
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
	public function get(): Schema
	{
		return $this->schema;
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
	}
	
	/**
	 * Uses the provided handler to import a new schema
	 *
	 * @return $this
	 */
	public function from(SchemaHandlerInterface $handler)
	{
		$this->schema = $handler->import();
		$this->errors = array_merge($this->errors, $handler->getErrors());
		return $this;
	}
	
	/**
	 * Uses the provided handler to export the current schema
	 *
	 * @return $this
	 */
	public function to(SchemaHandlerInterface $handler)
	{
		$handler->export($this->schema);
		$this->errors = array_merge($this->errors, $handler->getErrors());
		return $this;
	}
}
