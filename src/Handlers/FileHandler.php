<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;

class FileHandler extends BaseHandler implements SchemaHandlerInterface
{
	// Initiate library
	public function __construct(BaseConfig $config = null)
	{
		parent::__construct($config);
	}
	
	// Scan the schemas directory and process any files found
	public function get(): ?Schema
	{
		helper('filesystem');
		$files = get_filenames($this->config->schemasDirectory, true);

		if (empty($files))
		{
			$this->errors[] = lang('Schemas.emptySchemaDirectory', [$this->config->schemasDirectory]);
			return null;
		}
		
		// Try each file
		foreach ($files as $path)
		{
			// Make sure there is a handler for this extension
			$handler = $this->getHandlerForFile($path);
			if (is_null($handler))
			{
				$this->errors[] = lang('Schemas.unsupportedHandler', [pathinfo($path, PATHINFO_EXTENSION)]);
				continue;
			}
			
			if (empty($schema))
			{
				$schema = $handler->get();
			}
			else
			{
				$schema->merge($handler->get());
			}
		}
		
		return $schema ?? null;
	}
	
	public function save(Schema $schema): bool
	{
		$this->methodNotImplemented(__CLASS__, 'save');
		return false;
	}
}
