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

class XmlHandler extends FileHandler implements SchemaHandlerInterface
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
		// Pass through to FileHandler
		parent::__construct($config, $path);
	}
	
	// 
	public function get(): ?Schema
	{
		$contents = $this->getContents();
		if (is_null($contents))
			return;
		
		
	}
	
	// Write out the schema into an XML file
	public function save(Schema $schema): bool
	{
		$this->methodNotImplemented(__CLASS__, 'save');
		return false;

		helper('xml');
		
	}
}
