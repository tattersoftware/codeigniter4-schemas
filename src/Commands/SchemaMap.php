<?php namespace Tatter\Schema\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\Schema\Mapper;

class SchemaMap extends BaseCommand
{
    protected $group       = 'Schema';
    protected $name        = 'schema:map';
    protected $description = 'Generate a new schema of a database.';

    public function run(array $params)
    {
		$mapper = new Mapper();

	}
}
