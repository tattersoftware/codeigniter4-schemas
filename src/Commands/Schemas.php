<?php namespace Tatter\Schemas\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Schemas extends BaseCommand
{
    protected $group       = 'Schemas';
    protected $name        = 'schemas';
    protected $description = 'Manage database schemas.';
    
	protected $usage     = "schemas [from] [-to handler]";
	protected $arguments = [
		'from'  => 'The handler to use for importing the schema',
	];
	protected $options = [
		'-to'   => 'Optional handler for exporting the schema; defaults to standard output',
	];

	public function run(array $params = [])
    {
		$schemas = service('schemas');
		
		// Consume or prompt for the "from" handler
		$from = array_shift($params);
		if (empty($from))
		{
			$from = CLI::prompt('Name of the import handler', 'Database', 'required');
		}
		$to = $params['-to'] ?? CLI::getOption('to') ?? 'output';		
		
		// Try the import
		try
		{
			$schemas->from($from);
		}
		catch (\Exception $e)
		{
			$this->showError($e);
		}
		
		// Intercept output requests
		if ($to == 'output')
		{
			$schema = $schemas->get();
			d($schema);
			return;
		}
		
		// Try the export
		try
		{
			$schemas->to($to);
		}
		catch (\Exception $e)
		{
			$this->showError($e);
		}
	}
}
