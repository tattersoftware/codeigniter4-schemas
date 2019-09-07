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

class Schema extends \Tatter\Schema\Config\Schema
{
	// whether to continue instead of throwing exceptions
	public $silent = true;
}
