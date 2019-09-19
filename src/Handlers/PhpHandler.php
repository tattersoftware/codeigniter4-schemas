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

class PhpHandler extends BaseHandler implements SchemaHandlerInterface
{
	/**
	 * The file path.
	 *
	 * @var string
	 */
	protected $path;
	
	// Initiate library
	public function __construct(BaseConfig $config = null, $path = null)
	{
		parent::__construct($config);
		
		// Save the path
		$this->path = $path;
	}
	
	// Read in data from the file and fit it into a schema
	public function get(): ?Schema
	{
		$contents = $this->getContents($this->path);
		if (is_null($contents))
		{
			$this->errors[] = lang('Schemas.emptySchemaFile', [$this->path]);
			return null;
		}
		
		// PHP files should contain pre-built schemas in the $schema variable
		// So the path just needs to be included and the variable checked
		try {
			require $this->path;
		} catch (\Exception $e) {
			$this->errors[] = $e->getMessage();
			return null;
		}

		return $schema ?? null;
	}
	
	// Write out the schema to file
	public function save(Schema $schema): bool
	{
		$this->methodNotImplemented(__CLASS__, 'save');
		return false;		
	}
}
