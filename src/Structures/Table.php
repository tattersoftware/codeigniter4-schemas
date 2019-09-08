<?php namespace Tatter\Schemas\Structures;

class Table
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	public $name;
	
	/**
	 * Relationships this table has with others
	 *
	 * @var array of Relations
	 */
	public $relations = [];
	
	public function __construct($name = null)
	{
		$this->name = $name ?? null;
	}
}
