<?php namespace Tatter\Schemas\Structures;

class Table extends BaseStructure
{
	/**
	 * Initialize required fields.
	 */
	public function __construct($name = null)
	{
		$this->name        = $name;
		//$this->pivot       = false;
		$this->fields      = [];
		$this->indexes     = [];
		$this->foreignKeys = [];
		$this->relations   = [];
	}
	
	/**
	 * Merges data from one table into the other; latter overwrites.
	 *
	 * @return $this
	 *
	public function merge(Table $table): Table
	{
		$this->name  = $table->name;
		$this->pivot = $this->pivot || $table->pivot;
		
		foreach ($schema->tables as $tableName => $table)
		{
			if (isset($this->tables[$tableName]))
			{
				$this->tables[$tableName] = $this->tables[$tableName]->merge($table);
			}
			else
			{
				$this->tables[$tableName] = $table;
			}
		}
		
		return $this;
	}
*/
}
