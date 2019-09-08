<?php namespace Tatter\Schemas\Structures;

class Schema
{
	/**
	 * The database group this schema defines.
	 *
	 * @var string
	 */
	public $group;
	
	/**
	 * This database's tables.
	 *
	 * @var array of Tables
	 */
	public $tables;
	
	public function __construct($group = null)
	{
		$this->group = $group ?? null;
	}
}
