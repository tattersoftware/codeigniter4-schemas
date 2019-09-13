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
	
	// Whether to ignore the migrations table
	public $ignoreMigrationsTable = true;
	
	// Default time-to-live for a stored schema (e.g. Cache)
	public $ttl = 6000;
}