<?php namespace Tatter\Schemas\Structures;

class Schema
{	
	/**
	 * The schema tables.
	 *
	 * @var array of Tables
	 */
	public $tables;
	
	/**
	 * Merges data from one schema into the other; latter overwrites.
	 *
	 * @return $this
	 */
	public function merge(Schema $schema): Schema
	{
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
}
