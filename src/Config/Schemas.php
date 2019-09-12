<?php namespace Tatter\Schemas\Config;

use CodeIgniter\Config\BaseConfig;

class Schemas extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Whether to ignore the migrations table
	public $ignoreMigrationsTable = true;
	
	// Whether to stick to just tables of the defined prefix
	// (Also removes prefixes from table names)
	public $constrainByPrefix = true;
	
	// Default time-to-live for a stored schema (e.g. Cache)
	public $ttl = 6000;
}
