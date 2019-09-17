<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use Tatter\Schemas\Exceptions\SchemasException;

class FileHandler extends BaseHandler
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
		
		// Guess at a default file location
		if (is_null($file))
		{
			$this->file = WRITEPATH . 'uploads/schema.xml';
		}
		// Save injected file path
		else
		{
			$this->path = $path;
		}
		
	}
	
	// Change the path
	public function setPath(string $path)
	{
		$this->path = $path;
		return $this;
	}
	
	// Get the path
	public function getPath()
	{
		return $this->path;
	}
	
	// Validate the current file and get its contents
	public function getContents(): ?string
	{
		if (! is_file($this->path))
		{
			if ($this->config->silent)
			{
				$this->errors[] = lang('Files.fileNotFound', [$this->path]);
				return null;
			}
			else
			{
				throw FileNotFoundException::forFileNotFound($this->path);
			}
		}
		
		return file_get_contents($this->path);
	}
	
	// Validate the target file and write to it
	public function putContents(string $data): bool
	{
		if (! is_file($this->path))
		{
// WIP - should try to create the file
			if ($this->config->silent)
			{
				$this->errors[] = lang('Files.fileNotFound', [$this->path]);
				return null;
			}
			else
			{
				throw FileNotFoundException::forFileNotFound($this->path);
			}
		}
		
		return (bool)file_put_contents($this->path, $data);
	}
}
