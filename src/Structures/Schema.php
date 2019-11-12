<?php namespace Tatter\Schemas\Structures;

class Schema extends Mergeable
{	
	/**
	 * The schema tables.
	 *
	 * @var Mergeable of Tables
	 */
	public $tables;

	/**
	 * Set up the tables property. If a ReaderHandler was passed
	 * then use it, which will generate Mergeables on return.
	 * Otherwise use an empty, generic Mergeable.
	 *
	 * @param BaseConfig  $config   The library config
	 * @param string      $db       A database connection, or null to use the default
	 */
	public function __construct($reader = null)
	{
		$this->tables = $reader ?? new Mergeable();
	}
}
