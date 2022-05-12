<?php

namespace Tatter\Schemas\Drafter\Handlers;

use CodeIgniter\Model;
use Config\Services;
use Exception;
use Tatter\Schemas\Config\Schemas as SchemasConfig;
use Tatter\Schemas\Drafter\BaseDrafter;
use Tatter\Schemas\Drafter\DrafterInterface;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Table;

class ModelHandler extends BaseDrafter implements DrafterInterface
{
    /**
     * The default database group.
     *
     * @var string
     */
    protected $defaultGroup;

    /**
     * The database group to constrain by.
     *
     * @var string
     */
    protected $group;

    /**
     * Save the config and set the initial database group
     *
     * @param SchemasConfig $config The library config
     * @param string        $group  A database group to use as a filter; null = default group, false = no filtering
     */
    public function __construct(?SchemasConfig $config = null, $group = null)
    {
        parent::__construct($config);

        // Load the default database group
        $config             = config('Database');
        $this->defaultGroup = $config->defaultGroup;
        unset($config);

        // If nothing was specified then constrain to the default database group
        if (null === $group) {
            $this->group = $this->defaultGroup;
        } elseif (! empty($group)) {
            $this->group = $group;
        }
    }

    /**
     * Change the name of the database group constraint
     *
     * @param string $group A database group to use as a filter; false = no filtering
     */
    public function setGroup(string $group)
    {
        $this->group = $group;

        return $group;
    }

    /**
     * Get the name of the database group constraint
     *
     * @return string|null The current group
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * Load models and build table data off their properties
     */
    public function draft(): ?Schema
    {
        // Start with an empty schema
        $schema = new Schema();

        foreach ($this->getModels() as $class) {
            $instance = new $class();

            // Start a new table
            $table             = new Table($instance->table);
            $table->model      = $class;
            $table->returnType = $instance->returnType;

            // Create a field for the primary key
            $field                         = new Field($instance->primaryKey);
            $field->primary_key            = true;
            $table->fields->{$field->name} = $field;

            // Create a field for each allowed field
            foreach ($instance->allowedFields as $fieldName) {
                $field                       = new Field($fieldName);
                $table->fields->{$fieldName} = $field;
            }

            // Figure out which timestamp fields (if any) this model uses and add them
            $timestamps = $instance->useTimestamps ? [
                'createdField',
                'updatedField',
            ] : [];
            if ($instance->useSoftDeletes) {
                $timestamps[] = 'deletedField';
            }

            // Get field names from each timestamp attribute
            foreach ($timestamps as $attribute) {
                $fieldName   = $instance->{$attribute};
                $field       = new Field($fieldName);
                $field->type = $instance->dateFormat;

                $table->fields->{$fieldName} = $field;
            }

            $schema->tables->{$table->name} = $table;
        }

        return $schema;
    }

    /**
     * Load model class names from all namespaces, filtered by group
     *
     * @return array of model class names
     */
    protected function getModels(): array
    {
        $loader  = Services::autoloader();
        $locator = Services::locator();
        $models  = [];

        // Get each namespace
        foreach ($loader->getNamespace() as $namespace => $path) {
            // Skip namespaces that are ignored
            if (in_array($namespace, $this->config->ignoredNamespaces, true)) {
                continue;
            }

            // Get files under this namespace's "/Models" path
            foreach ($locator->listNamespaceFiles($namespace, '/Models/') as $file) {
                if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    // Load the file
                    require_once $file;
                }
            }
        }

        // Filter loaded class on likely models
        $classes = preg_grep('/model$/i', get_declared_classes());

        // Try to load each class
        foreach ($classes as $class) {
            // Check for ignored namespaces
            foreach ($this->config->ignoredNamespaces as $namespace) {
                if (strpos($class, $namespace) === 0) {
                    continue 2;
                }
            }

            // Make sure it's really a model
            if (! is_a($class, Model::class, true)) {
                continue;
            }

            // Try to instantiate
            try {
                $instance = new $class();
            } catch (Exception $e) {
                continue;
            }

            // Make sure it has a valid table
            $table = $instance->table;
            if (empty($table)) {
                continue;
            }

            // Filter by group
            $group = $instance->DBGroup ?? $this->defaultGroup; // @phpstan-ignore-line
            if (empty($this->group) || $group === $this->group) {
                $models[] = $class;
            }
            unset($instance);
        }

        return $models;
    }
}
