<?php namespace Tatter\Schemas\Structures;

class Relation
{
	/**
	 * The first table name.
	 *
	 * @var string
	 */
	public $table1;
	
	/**
	 * The second table name.
	 *
	 * @var string
	 */
	public $table1;
	
	
	public function __construct($table1 = null)
	{
		$this->table1 = $table1 ?? null;
	}
}
