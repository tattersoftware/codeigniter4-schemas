<?php namespace Tatter\Schemas\Structures;

class Schema extends Mergeable
{	
	/**
	 * The schema tables.
	 *
	 * @var Mergeable of Tables
	 */
	public $tables;
	
	public function __construct()
	{
		$this->tables = new Mergeable();
	}
}
