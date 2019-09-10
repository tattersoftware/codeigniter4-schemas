<?php namespace Tatter\Schemas\Structures;

class Relation
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
	 * The local field for direct relationships.
	 *
	 * @var string
	 */
	public $field;
	
	/**
	 * The foreign field for direct relationships.
	 *
	 * @var string
	 */
	public $foreignField;
	
	/**
	 * Tables and columns to pivot for "through" relationships.
	 *
	 * @var array of columnName => tableName
	 */
	public $pivots;
}
