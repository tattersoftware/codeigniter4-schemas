<?php namespace Tatter\Schemas\Structures;

class Schema extends BaseStructure
{
	/**
	 * Initialize with an array for tables.
	 */
	public function __construct()
	{
		$this->tables = [];
	}
	
	/**
	 * Merges data from one schema into the other; latter overwrites.
	 *
	 * @return $this
	 *
	public function merge(Schema $schema): Schema
	{
		foreach ($schema->tables as $tableName => $table)
		{
			if (isset($this->data['tables'][$tableName]))
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
