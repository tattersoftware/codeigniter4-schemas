<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\ForeignKey;

class DatabaseHandler extends BaseHandler implements SchemaHandlerInterface
{
	/**
	 * The main database connection.
	 *
	 * @var ConnectionInterface
	 */
	protected $db;

	/**
	 * The prefix for the database connection.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * The pattern used to identify potention relationship fields.
	 *
	 * @var string
	 */
	protected $fieldRegex = '/^.+_id$/';
	
	// Initiate library
	public function __construct(BaseConfig $config = null, $db = null)
	{		
		parent::__construct($config);
		
		// Use injected database connection, or start a new one with the default group
		$this->db     = db_connect($db);
		$this->prefix = $this->db->getPrefix();
	}
	
	// Map the database from $this->db into a new schema
	public function import(): ?Schema
	{
		// Start with a fresh Schema
		$schema = new Schema();
		
		// Track possible relations to check
		$tableRelations = [];
		$fieldRelations = [];
		
		// Track confirmed pivot table names
		$pivotTables = [];

		// Proceed table by table
		foreach ($this->db->listTables(true) as $tableName)
		{
			// Check for migrations table to ignore
			if ($this->config->ignoreMigrationsTable && ($tableName == 'migrations' || $tableName == $this->prefix.'migrations'))
			{
				continue;
			}
			$tableName = $this->stripPrefix($tableName);
			
			// Start a new table
			$table = new Table($tableName);
			
			// Check for a relation table indicator
			if (strpos($tableName, '_') !== false)
			{
				$tableRelations[] = $tableName;
			}
			
			// Proceed field by field
			foreach ($this->db->getFieldData($tableName) as $fieldData)
			{
				// Start a new field
				$field = new Field($fieldData);
				
				// Check for a relation field indicator
				if (! $field->primary_key && preg_match($this->fieldRegex, $field->name))
				{
					if (! isset($fieldRelations[$tableName]))
					{
						$fieldRelations[$tableName] = [];
					}
					$fieldRelations[$tableName][] = $field->name;
				}
				
				// Add the field to the table
				$table->fields[$field->name] = $field;
			}
			
			// Proceed index by index
			foreach ($this->db->getIndexData($tableName) as $indexData)
			{
				// Start a new index
				$index = new Index($indexData);
				
				// Add the index to the table
				$table->indexes[$index->name] = $index;
			}
			
			// Proceed FK by FK
			foreach ($this->db->getForeignKeyData($tableName) as $foreignKeyData)
			{
				// Start a new foreign key
				$foreignKey = new ForeignKey($foreignKeyData);
				
				// Resolve prefixes on any names
				$foreignKey->constraint_name = $this->stripPrefix($foreignKey->constraint_name);
				if (isset($foreignKey->table_name))
				{
					$foreignKey->table_name = $this->stripPrefix($foreignKey->table_name);
				}
				if (isset($foreignKey->table_name))
				{
					$foreignKey->foreign_table_name = $this->stripPrefix($foreignKey->foreign_table_name);
				}
				
				// Add the FK to the table
				$table->foreignKeys[$foreignKey->constraint_name] = $foreignKey;
				
				// Create a relation
				$relation = new Relation();
				$relation->type  = 'belongsTo';
				$relation->table = $this->stripPrefix($foreignKey->foreign_table_name);
				
				// Not all drivers supply the column names
				if (isset($foreignKey->column_name))
				{
					$pivot = [
						$foreignKey->foreign_table_name,
						$foreignKey->column_name,
						$foreignKey->foreign_column_name,
					];
					$relation->pivots = [$pivot];
				}
				
				// Add the relation to the table
				$table->relations[$relation->table] = $relation;
			}
			
			// Add the table to the schema
			$schema->tables[$table->name] = $table;
		}

		// Check tables flagged as possible pivots
		foreach ($tableRelations as $tableName)
		{
			list($tableName1, $tableName2) = explode('_', $tableName, 2);

			// Check for both tables (e.g. `groups_users` must have `groups` and `users`)			
			if (isset($schema->tables[$tableName1]) && isset($schema->tables[$tableName2]))
			{
				// A match! Look for foreign fields (may not be properly keyed)
				$fieldName1    = $this->findKeyToForeignTable($schema->tables[$tableName], $tableName1);
				$foreignField1 = $this->findPrimaryKey($schema->tables[$tableName1]);
				
				$fieldName2    = $this->findKeyToForeignTable($schema->tables[$tableName], $tableName2);
				$foreignField2 = $this->findPrimaryKey($schema->tables[$tableName2]);
			
				// If all fields were found we have a relation
				if ($fieldName1 && $fieldName2 && $foreignField1 && $foreignField2)
				{
					// Set the table as a pivot & clear its relations
					$schema->tables[$tableName]->pivot = true;
					$schema->tables[$tableName]->relations = [];
					$pivotTables[] = $tableName;

					// Build the pivots
					$pivot1 = [
						$tableName,       // groups_users
						$foreignField1,   // id
						$fieldName1,      // group_id
					];
					$pivot2 = [
						$tableName2,      // users
						$fieldName2,      // user_id
						$foreignField2,   // id
					];
					
					// Build the relation
					$relation = new Relation();
					$relation->type   = 'manyToMany';
					$relation->table  = $tableName2;
					$relation->pivots = [$pivot1, $pivot2];
					
					// Add it to the first table
					$schema->tables[$tableName1]->relations[$tableName2] = $relation;

					// Build the pivots
					$pivot1 = [
						$tableName,       // groups_users
						$foreignField2,   // id
						$fieldName2,      // user_id
					];
					$pivot2 = [
						$tableName1,      // groups
						$fieldName1,      // group_id
						$foreignField1,   // id
					];
					
					// Build the relation
					$relation = new Relation();
					$relation->type   = 'manyToMany';
					$relation->table  = $tableName1;
					$relation->pivots = [$pivot1, $pivot2];
					
					// Add it to the second table
					$schema->tables[$tableName2]->relations[$tableName1] = $relation;
				}
			}
		}
		
		// Check fields flagged as possible pivot points (e.g. records->user_id <-> users->id)
		foreach ($fieldRelations as $tableName1 => $fields)
		{
			foreach ($fields as $fieldName)
			{
				// Convert to a possible table name
				$tableName2 = plural(preg_replace('/_id$/', '', $fieldName, 1));

				// Check for the table (e.g. `user_id` must have `users`)
				if (isset($schema->tables[$tableName2]))
				{
					// A match! Get the key from the target table
					$foreignField = $this->findPrimaryKey($schema->tables[$tableName2]);
			
					// If the field was found we have a relation
					if ($foreignField)
					{
						// Build the pivot
						$pivot = [
							$tableName2,     // users
							$fieldName,      // user_id
							$foreignField,   // id
						];
					
						// Build the relation
						$relation = new Relation();
						$relation->type   = 'belongsTo';
						$relation->table  = $tableName2;
						$relation->pivots = [$pivot];
					
						// Add it to the first table
						$schema->tables[$tableName1]->relations[$tableName2] = $relation;

						// Build the reverse pivot
						$pivot = [
							$tableName1,     // records
							$foreignField,   // id
							$fieldName,      // user_id
						];
					
						// Build the inverse relation
						$relation = new Relation();
						$relation->type   = 'hasMany';
						$relation->table  = $tableName1;
						$relation->pivots = [$pivot];
					
						// Add it to the second table
						$schema->tables[$tableName2]->relations[$tableName1] = $relation;
					}
				}
			}
		}
		
		// Clear pivots from any relations
		foreach ($pivotTables as $pivotTableName)
		{
			// Blank this table's relations
			$schema->tables[$pivotTableName]->relations = [];
						
			// Remove the table from other relations
			foreach ($schema->tables as $tableName => $table)
			{
				unset($schema->tables[$tableName]->relations[$pivotTableName]);
			}
		}
		
		return $schema;
	}
	
	// Create all the schema structures in the database
	public function export(Schema $schema): bool
	{
		return false;
	}
	
	/**
	 * Return a string without DBPrefix.
	 *
	 * @param string    $str  Name of a database object
	 *
	 * @return string   The updated name
	 */
	protected function stripPrefix(string $str): string
	{
		if (empty($str) || empty($this->prefix))
			return $str;

		// Strip the first occurence of the prefix
		return preg_replace("/^{$this->prefix}/", '', $str, 1);
	}
}
