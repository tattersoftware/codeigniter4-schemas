<?php namespace Tatter\Schemas\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\Schema\Mapper;

class Schemas extends BaseCommand
{
    protected $group       = 'Schemas';
    protected $name        = 'schemas';
    protected $description = 'Map a new schema of a database.';

    public function run(array $params)
    {
		$schemas = service('schemas');
		$schema = $schemas->get();
		var_dump($schema);
	}
}
