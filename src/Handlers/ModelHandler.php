<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
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
	 * The database group to constrain by.
	 *
	 * @var string
	 */
	protected $group;
	
	// Initiate library
	public function __construct(BaseConfig $config = null, $group = null)
	{		
		parent::__construct($config);
		
		// By default constrain to the default database group
		if (is_null($DBGroup))
		{
			$config      = config('Database');
			$this->group = $config->defaultGroup;
			unset($config);
			$this->group = $group;
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
	

	public function import(): ?Schema
	{
		$schema = new Schema();
		
		return $schema;
	}
	
	// Not yet implemented
	public function export(Schema $schema): bool
	{
		return false;
	}
	
	/**
	 * Use the ReflectionHelper trait to get the protected "table" property.
	 *
	 * @param mixed    $model  A model instance or class name
	 *
	 * @return string  The name of the table for this model
	 */
	protected function getModelTable($model): string
	{
		if (is_string($model))
		{
			$model = new $model();
		}
		
		return $this->getPrivateProperty($model, 'table');
	}
}
