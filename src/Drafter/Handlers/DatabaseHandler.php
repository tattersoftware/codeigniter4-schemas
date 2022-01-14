<?php

namespace Tatter\Schemas\Drafter\Handlers;

use CodeIgniter\Database\BaseConnection;
use Tatter\Schemas\Config\Schemas as SchemasConfig;
use Tatter\Schemas\Drafter\BaseDrafter;
use Tatter\Schemas\Drafter\DrafterInterface;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\ForeignKey;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Table;

class DatabaseHandler extends BaseDrafter implements DrafterInterface
{
    /**
     * The main database connection.
     *
     * @var BaseConnection
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

    /**
     * Save the config and set up the database connection
     *
     * @param SchemasConfig $config The library config
     * @param string        $db     A database connection, or null to use the default
     */
    public function __construct(?SchemasConfig $config = null, $db = null)
    {
        parent::__construct($config);

        // Use injected database connection, or start a new one with the default group
        $this->db     = db_connect($db);
        $this->prefix = $this->db->getPrefix();
    }

    /**
     * Map the database from $this->db into a new schema
     */
    public function draft(): ?Schema
    {
        // Start with a fresh schema
        $schema = new Schema();

        // Track possible relations to check
        $tableRelations = [];
        $fieldRelations = [];

        // Track confirmed pivot table names
        $pivotTables = [];

        // Create all the tables
        foreach ($this->db->listTables(true) as $tableName) {
            // Check for ignored tables
            if (in_array($tableName, $this->config->ignoredTables, true)) {
                continue;
            }

            // Strip the prefix and check again`
            $tableName = $this->stripPrefix($tableName);
            if (in_array($tableName, $this->config->ignoredTables, true)) {
                continue;
            }

            // Create the table in the schema
            $schema->tables->{$tableName} = new Table($tableName);
        }

        // Analyze each table
        foreach ($schema->tables as $table) {
            // Check for a relation table indicator
            if (strpos($table->name, '_') !== false) {
                $tableRelations[] = $table->name;
            }

            // Proceed field by field
            foreach ($this->db->getFieldData($table->name) as $fieldData) {
                // Start a new field
                $field = new Field($fieldData);

                // Check for a relation field indicator
                if (! $field->primary_key && preg_match($this->fieldRegex, $field->name)) {
                    if (! isset($fieldRelations[$table->name])) {
                        $fieldRelations[$table->name] = [];
                    }
                    $fieldRelations[$table->name][] = $field->name;
                }

                // Add the field to the schema
                $schema->tables->{$table->name}->fields->{$field->name} = $field;
            }

            // Proceed index by index
            foreach ($this->db->getIndexData($table->name) as $indexData) {
                // Start a new index
                $index = new Index($indexData);

                // Add the index to the schema
                $schema->tables->{$table->name}->indexes->{$index->name} = $index;
            }

            // Proceed FK by FK
            foreach ($this->db->getForeignKeyData($table->name) as $foreignKeyData) {
                // Start a new foreign key
                $foreignKey = new ForeignKey($foreignKeyData);

                // Resolve prefixes on any names
                $foreignKey->constraint_name = $this->stripPrefix($foreignKey->constraint_name);
                if (isset($foreignKey->table_name)) {
                    $foreignKey->table_name = $this->stripPrefix($foreignKey->table_name);
                }
                if (isset($foreignKey->foreign_table_name)) {
                    $foreignKey->foreign_table_name = $this->stripPrefix($foreignKey->foreign_table_name);
                }

                // Add the FK to the schema
                $schema->tables->{$table->name}->foreignKeys->{$foreignKey->constraint_name} = $foreignKey;

                // Create a relation
                $relation            = new Relation();
                $relation->type      = 'belongsTo';
                $relation->table     = $foreignKey->foreign_table_name;
                $relation->singleton = true;

                // Not all drivers supply the column names
                if (isset($foreignKey->column_name)) {
                    $pivot = [
                        $foreignKey->table_name,
                        $foreignKey->column_name,
                        $foreignKey->foreign_table_name,
                        $foreignKey->foreign_column_name,
                    ];
                    $relation->pivots = [$pivot];
                }

                // Add the relation to the schema
                $schema->tables->{$table->name}->relations->{$relation->table} = $relation;

                // Create the inverse relation
                $relation        = new Relation();
                $relation->type  = 'hasMany';
                $relation->table = $foreignKey->table_name;

                // Not all drivers supply the column names
                if (isset($foreignKey->column_name)) {
                    $pivot = [
                        $foreignKey->foreign_table_name,
                        $foreignKey->foreign_column_name,
                        $foreignKey->table_name,
                        $foreignKey->column_name,
                    ];
                    $relation->pivots = [$pivot];
                }

                // Add the relation to the table
                $schema->tables->{$foreignKey->foreign_table_name}->relations->{$relation->table} = $relation;
            }
        }

        // Check tables flagged as possible pivots
        foreach ($tableRelations as $tableName) {
            [$tableName1, $tableName2] = explode('_', $tableName, 2);

            // Check for both tables (e.g. `groups_users` must have `groups` and `users`)
            if (isset($schema->tables->{$tableName1}, $schema->tables->{$tableName2})) {
                // A match! Look for foreign fields (may not be properly keyed)
                $fieldName1    = $this->findKeyToForeignTable($schema->tables->{$tableName}, $tableName1);
                $foreignField1 = $this->findPrimaryKey($schema->tables->{$tableName1});

                $fieldName2    = $this->findKeyToForeignTable($schema->tables->{$tableName}, $tableName2);
                $foreignField2 = $this->findPrimaryKey($schema->tables->{$tableName2});

                // If all fields were found we have a relation
                if ($fieldName1 && $fieldName2 && $foreignField1 && $foreignField2) {
                    // Set the table as a pivot & clear its relations
                    $schema->tables->{$tableName}->pivot     = true;
                    $schema->tables->{$tableName}->relations = new Mergeable();
                    $pivotTables[]                           = $tableName;

                    // Build the pivots
                    $pivot1 = [
                        $tableName1,      // groups
                        $foreignField1,   // id
                        $tableName,       // groups_users
                        $fieldName1,      // group_id
                    ];
                    $pivot2 = [
                        $tableName,       // groups_users
                        $fieldName2,      // user_id
                        $tableName2,      // users
                        $foreignField2,   // id
                    ];

                    // Build the relation
                    $relation         = new Relation();
                    $relation->type   = 'manyToMany';
                    $relation->table  = $tableName2;
                    $relation->pivots = [
                        $pivot1,
                        $pivot2,
                    ];

                    // Add it to the first table
                    $schema->tables->{$tableName1}->relations->{$tableName2} = $relation;

                    // Build the pivots
                    $pivot1 = [
                        $tableName2,      // users
                        $foreignField2,   // id
                        $tableName,       // groups_users
                        $fieldName2,      // user_id
                    ];
                    $pivot2 = [
                        $tableName,       // groups_users
                        $fieldName1,      // group_id
                        $tableName1,      // groups
                        $foreignField1,   // id
                    ];

                    // Build the relation
                    $relation         = new Relation();
                    $relation->type   = 'manyToMany';
                    $relation->table  = $tableName1;
                    $relation->pivots = [
                        $pivot1,
                        $pivot2,
                    ];

                    // Add it to the second table
                    $schema->tables->{$tableName2}->relations->{$tableName1} = $relation;
                }
            }
        }

        // Check fields flagged as possible pivot points (e.g. records->user_id <-> users->id)
        foreach ($fieldRelations as $tableName1 => $fields) {
            foreach ($fields as $fieldName) {
                // Convert to a possible table name
                $tableName2 = plural(preg_replace('/_id$/', '', $fieldName, 1));

                // Check for the table (e.g. `user_id` must have `users`)
                if (isset($schema->tables->{$tableName2})) {
                    // A match! Get the key from the target table
                    $foreignField = $this->findPrimaryKey($schema->tables->{$tableName2});

                    // If the field was found we have a relation
                    if ($foreignField) {
                        // Build the pivot
                        $pivot = [
                            $tableName1,     // records
                            $fieldName,      // user_id
                            $tableName2,     // users
                            $foreignField,   // id
                        ];

                        // Build the relation
                        $relation            = new Relation();
                        $relation->type      = 'belongsTo';
                        $relation->singleton = true;
                        $relation->table     = $tableName2;
                        $relation->pivots    = [$pivot];

                        // Add it to the first table
                        $schema->tables->{$tableName1}->relations->{$tableName2} = $relation;

                        // Build the reverse pivot
                        $pivot = [
                            $tableName2,     // users
                            $foreignField,   // id
                            $tableName1,     // records
                            $fieldName,      // user_id
                        ];

                        // Build the inverse relation
                        $relation         = new Relation();
                        $relation->type   = 'hasMany';
                        $relation->table  = $tableName1;
                        $relation->pivots = [$pivot];

                        // Add it to the second table
                        $schema->tables->{$tableName2}->relations->{$tableName1} = $relation;
                    }
                }
            }
        }

        // Clear pivots from any relations
        foreach ($pivotTables as $pivotTableName) {
            // Blank this table's relations
            $schema->tables->{$pivotTableName}->relations = new Mergeable();

            // Remove the table from other relations
            foreach ($schema->tables as $tableName => $table) {
                unset($schema->tables->{$tableName}->relations->{$pivotTableName});
            }
        }

        return $schema;
    }

    /**
     * Return a database object name without its prefix.
     *
     * @param string $str Name of a database object
     *
     * @return string The updated name
     */
    protected function stripPrefix(string $str): string
    {
        if (empty($str) || empty($this->prefix)) {
            return $str;
        }

        // Strip the first occurence of the prefix
        return preg_replace("/^{$this->prefix}/", '', $str, 1);
    }
}
