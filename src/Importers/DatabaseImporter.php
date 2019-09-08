<?php namespace Tatter\Schemas\Importers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;

class DatabaseImporter extends BaseImporter
{
	/**
	 * The main database connection.
	 *
	 * @var ConnectionInterface
	 */
	protected $db;

	/**
	 * The database group to map.
	 *
	 * @var string
	 */
	protected $group;

	/**
	 * The pattern used to identify potention relationship fields.
	 *
	 * @var string
	 */
	protected $fieldRegex = '/^.+_id$/';
	
	// Initiate library
	public function __construct(BaseConfig $config, $db = null)
	{		
		parent::__construct($config);
		
		// Get the default database group
		$config      = config('Database');
		$this->group = $config->defaultGroup;
		unset($config);

		// Use injected database connection, or start a new one with the default group
		$this->db = db_connect($db);
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
	
	// Mapping the database from $this->group into a new schema
	public function import()
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
}
