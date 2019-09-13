<?php namespace Tatter\Schemas\Structures;

class Relation extends Mergeable
{
	/**
	 * The type of relationship.
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 * The related table name.
	 *
	 * @var string
	 */
	public $table;
	
	/**
	 * Tables and columns for pivot and "through" relationships.
	 *
	 * @var Array of [tableName, fieldName, foreignField]
	 */
	public $pivots = [];
	
	public function __construct()
	{
		$this->pivots = new Mergeable();
	}
}
