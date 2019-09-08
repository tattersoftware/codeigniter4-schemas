<?php namespace Tatter\Schemas\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\Schema\Mapper;

class SchemasMap extends BaseCommand
{
    protected $group       = 'Schemas';
    protected $name        = 'schemas:map';
    protected $description = 'Map a new schema of a database.';

    public function run(array $params)
    {
		$mapper = new Mapper();

	}
}
