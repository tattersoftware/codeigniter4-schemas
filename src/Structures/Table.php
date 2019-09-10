<?php namespace Tatter\Schemas\Structures;

class Table
{
	/**
	 * The table name.
	 *
	 * @var ?string
	 */
	public $name;
	
	/**
	 * Whether the table is a pivot.
	 *
	 * @var bool
	 */
	public $pivot = false;
	
	/**
	 * The table's fields.
	 *
	 * @var array of Field objects
	 */
	public $fields = [];
	
	/**
	 * The table's indices.
	 *
	 * @var array of Index objects
	 */
	public $indexes = [];
	
	/**
	 * The table's foreign keys.
	 *
	 * @var array of ForeignKey objects
	 */
	public $foreignKeys = [];
	
	/**
	 * Relationships this table has with others
	 *
	 * @var array of Relations
	 */
	public $relations = [];
	
	public function __construct($name = null)
	{
		$this->name = $name;
	}
}
