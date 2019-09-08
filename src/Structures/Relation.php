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
	public $table2;
	
	/**
	 * The pivot table name.
	 *
	 * @var string
	 */
	public $pivot;
	
	/**
	 * The field of the related key in table 1.
	 *
	 * @var string
	 */
	public $field1;
	
	/**
	 * The field of the related key in table 2.
	 *
	 * @var string
	 */
	public $field2;
	
	/**
	 * The type of relationship.
	 *
	 * @var string
	 */
	public $type;
	
	
	public function __construct($table1 = null)
	{
		$this->table1 = $table1 ?? null;
	}
}
