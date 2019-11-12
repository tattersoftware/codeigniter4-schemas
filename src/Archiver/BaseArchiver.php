<?php namespace Tatter\Schemas\Archiver;

use Tatter\Schemas\BaseHandler;

class BaseArchiver extends BaseHandler
{
	// COMMON FUNCTIONS FOR FILE HANDLERS
	
	/**
	 * Validate or create a file and write to it.
	 *
	 * @param string $path    The path to the file
	 *
	 * @throws 
	 */
	protected function putContents($path, string $data): bool
	{
		$file = new File($path);
		
		if (! $file->isWritable())
		{
			if ($this->config->silent)
			{
				$this->errors[] = lang('Files.fileNotFound', [$path]);
				return null;
			}
			else
			{
				throw FileNotFoundException::forFileNotFound($path);
			}
		}

	    $file = $file->openFile('w');
		return (bool)$file->fwrite($data);
	}
}
