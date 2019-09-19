<?php namespace Tatter\Schemas\Config;

use CodeIgniter\Config\BaseConfig;

class Schemas extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Default handlers used to generate a schema
	// (Probably shouldn't change this unless you really know what you're doing)
	public $defaultHandlers = ['database', 'model'];
	
	// Whether to ignore the migrations table
	public $ignoreMigrationsTable = true;
	
	// Default time-to-live for a stored schema (e.g. Cache)
	public $ttl = 6000;
	
	// Path to a folder to scan for schema files 
	public $schemasDirectory = APPPATH . 'Schemas';
}
