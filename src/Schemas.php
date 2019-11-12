<?php namespace Tatter\Schemas;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Structures\Schema;

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
		
		// Store an initial schema
		$this->schema = $schema ?? new Schema();
	}

	/**
	 * Return and clear any error messages
	 *
	 * @return array  String error messages
	 */
	public function getErrors(): array
	{
		$tmpErrors    = $this->errors;
		$this->errors = [];
		return $this->errors;
	}

	/**
	 * Return the current schema
	 *
	 * @return Schema  The current schema object
	 */
	public function get(): ?Schema
	{
		return $this->schema;
	}

	/**
	 * Draft a new schema from the given or default handler(s)
	 *
	 * @param array|string|null  $handlers
	 *
	 * @return $this
	 */
	public function draft($handlers = null)
	{
		if (empty($handlers))
		{
			$handlers = $this->config->draftHandlers;
		}
		
		// Wrap singletons
		if (! is_array($handlers))
		{
			$handlers = [$handlers];
		}
		
		// Draft and merge the schema from each handler in order
		foreach ($handlers as $handler)
		{
			$drafter = new $handler($this->config);

			$this->schema->merge($drafter->draft());

			$this->errors = array_merge($this->errors, $drafter->getErrors());
		}

		return $this;
	}
	
	/**
	 * Archive a copy of the current schema using the handler(s)
	 *
	 * @param array|string|null  $handlers
	 *
	 * @return $this
	 */
	public function archive($handlers = null)
	{
		if (empty($handlers))
		{
			$handlers = $this->config->archiveHandlers;
		}
		
		// Wrap singletons
		if (! is_array($handlers))
		{
			$handlers = [$handlers];
		}
		
		// Archive a copy to each handler's destination
		foreach ($handlers as $handler)
		{
			$archiver = new $handler($this->config);

			$handler->archive($this->schema);
			
			$this->errors = array_merge($this->errors, $archiver->getErrors());
		}

		return $this;
	}
	
	/**
	 * Read in a schema from the given or default handler
	 *
	 * @param array|string|null  $handlers
	 *
	 * @return $this
	 */
	public function read($handler = null)
	{
		if (empty($handler))
		{
			$handler = $this->config->readHandler;
		}

		// Create the reader instance
		$reader = new $handler($this->config);
		$this->errors = array_merge($this->errors, $reader->getErrors());

		// Replace the current schema with a new one using the injected readHandler
		$this->schema = new Schema($reader);

		return $this;
	}
}
