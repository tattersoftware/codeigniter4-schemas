<?php namespace Tatter\Schemas;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;

class Mapper
{
	// Trait the Reflection helper to get table names from models
	use \CodeIgniter\Test\ReflectionHelper;
	
	/**
	 * The main database connection.
	 *
	 * @var ConnectionInterface
	 */
	protected $db;
	
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Schemas\Config\Schema
	 */
	protected $config;

	/**
	 * The database group to map.
	 *
	 * @var string
	 */
	protected $group;

	/**
	 * The pattern used to identify potention relation fields.
	 *
	 * @var string
	 */
	protected $fieldRegex = '/^.+_id$/';
	
	/**
	 * Array of error messages assigned on failure
	 *
	 * @var array
	 */
	protected $errors = [];
	
	// Initiate library
	public function __construct(BaseConfig $config, $db = null)
	{		
		// Save the configuration
		$this->config = $config;
		
		// Get default database group
		$config      = config('Database');
		$this->group = $config->defaultGroup;
		unset($config);

		// Use injected database connection, or start a new one with the default group
		$this->db = db_connect($db);
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 * Set the database group.
	 *
	 * @param string $group
	 *
	 * @return $this
	 */
	public function setGroup(string $group)
	{
		$this->group = $group;
		return $this;
	}
	
	// Handle the mapping process
	public function run()
	{
		// Start with a fresh Schema
		$schema = new Schema();
		
		// Track possible relations to come back and discern
		$possibleRelations = [];
		
		// Proceed table by table
		foreach ($this->db->listTables() as $tableName)
		{
			// Start a new table
			$table = new Table($tableName);
			
			// Proceed field by field
			foreach ($this->db->getFieldData($tableName) as $fieldData)
			{
				// Start a new field
				$field = new Field($fieldData);
				
				// Check for a relation indicator
				if (! $field->primary_key && preg_match($this->regex, $field->name))
				{
					// Start a new Relation
					$relation = new Relation(); //WIP
				}
				$table->fields[$field->name] = $field;
			}
			
			
			$schema->tables[$table->name] = $table;
		}
		
		return $schema;
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
