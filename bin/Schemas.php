<?php namespace Config;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Schemas extends \Tatter\Schemas\Config\Schemas
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;
	
	// Default handlers used to generate a schema
	// (Probably shouldn't change this unless you really know what you're doing)
	public $defaultHandlers = ['database', 'model', 'file'];
	
	// Tables to ignore when creating the schema
	public $ignoredTables = ['migrations'];
	
	// Default time-to-live for a stored schema (e.g. Cache)
	public $ttl = 6000;
	
	// Path to a folder to scan for schema files 
	public $schemasDirectory = APPPATH . 'Schemas';
}
