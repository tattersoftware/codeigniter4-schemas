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
	 * Tables and columns to pivot for "through" relationships.
	 *
	 * @var array of [tableName, fieldName, foreignField]
	 */
	public $pivots;
}
