<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use Config\Services;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\ForeignKey;

class ModelHandler extends BaseHandler implements SchemaHandlerInterface
{
	// Trait the Reflection helper to get table names from models
	use \CodeIgniter\Test\ReflectionHelper;
	
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
	
	// Initiate library
	public function __construct(BaseConfig $config = null, $group = null)
	{		
		parent::__construct($config);
		
		// Load the default database group		
		$config = config('Database');
		$this->defaultGroup = $config->defaultGroup;
		unset($config);
		
		// If nothing was specified then constrain to the default database group
		if (is_null($group))
		{
			$this->group = $this->defaultGroup;
		}
		elseif (! empty($group))
		{
			$this->group = $group;
		}
	}
	
	// Change the name of the database group constraint
	public function setGroup(string $group)
	{
		$this->group = $group;
		return $group;
	}
	
	// Get the name of the database group constraint
	public function getGroup(): ?string
	{
		return $this->group;
	}
	
	// Load models and build table data off their properties
	public function import(): ?Schema
	{
		$schema = new Schema();
		//availalbe model properties: 'primaryKey', 'table', 'returnType', 'DBGroup'
		foreach ($this->getModels() as $class)
		{
			// Instantiate the model to harvest its properties
			$model = new $class();
			
			// Start a new table
			$table = new Table($model->table);
		
			$schema->tables[$table->name] = $table;
		}
		
		return $schema;
	}
	
	// Not yet implemented
	public function export(Schema $schema): bool
	{
		return false;
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
		$models = [];

		// Get each namespace
		foreach ($loader->getNamespace() as $namespace => $path)
		{
			// Get files under this namespace's "/Models" path
			foreach ($locator->listNamespaceFiles($namespace, '/Models/') as $file)
			{
				// Load the file
				require_once $file;
			}
		}
		
		// Filter loaded class on likely models
		$classes = preg_grep('/model/i', get_declared_classes());
		
		// Try to load each class
		foreach ($classes as $class)
		{
			// Try to instantiate
			try { $instance = new $class(); }
			catch (\Exception $e) { continue; }
			
			// Make sure it's really a model
			if (! ($instance instanceof \CodeIgniter\Model))
			{
				continue;
			}
			
			// Make sure it has a valid table
			$table = $instance->table;
			if (empty($table))
			{
				continue;
			}
			
			// Filter by group
			$group = $instance->DBGroup ?? $this->defaultGroup;
			if (empty($this->group) || $group == $this->group)
			{
				$models[] = $class;
			}
			unset($instance);
		}
		
		return $models;
	}
}
