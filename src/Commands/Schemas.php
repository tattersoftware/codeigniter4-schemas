<?php namespace Tatter\Schemas\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Schemas extends BaseCommand
{
    protected $group       = 'Schemas';
    protected $name        = 'schemas';
    protected $description = 'Manage database schemas.';
    
	protected $usage     = "schemas [import_handler ...] [-export handler]";
	protected $arguments = [
		'import_handler' => 'The handler(s) to use for importing the schema',
	];
	protected $options = [
		'-export' => 'Optional handler for exporting the schema; defaults to standard output',
	];

	public function run(array $handlers = [])
    {
		$schemas = service('schemas');
		
		// If no handlers provided then prompt for one
		if (empty($handlers))
		{
			$handler[] = CLI::prompt('Name of the first import handler', 'Database', 'required');
			while ($handler = CLI::prompt('Name of the next import handler'))
			{
				$handlers[] = $handler;
			}
		}
		$export = $params['-export'] ?? CLI::getOption('export') ?? 'output';		
		
		// Try the import
		try
		{
			$schemas->import(...$handlers);
		}
		catch (\Exception $e)
		{
			$this->showError($e);
		}
		
		// Intercept output requests
		if ($export == 'output')
		{
			$schema = $schemas->get();
			d($schema);
			return;
		}
		
		// Try the export
		try
		{
			$result = $schemas->export($export);
		}
		catch (\Exception $e)
		{
			$this->showError($e);
		}
		
		if (! $result)
		{
			CLI::write('Export failed!', 'red');
			foreach ($schemas->getErrors() as $error)
			{
				CLI::write($error, 'yellow');
			}
			return;
		}
		
		CLI::write("New schema exported to {$export} from " . implode(', ', $handlers), 'green');
	}
}
