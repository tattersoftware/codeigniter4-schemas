<?php namespace Tatter\Schemas\Structures;

class Field extends BaseStructure
{
	/**
	 * Initialize required fields.
	 */
	public function __construct($fieldData = null)
	{
		//$this->primary_key = false;
		if (empty($fieldData))
			return;
		
		if (is_string($fieldData))
		{
			$this->name = $fieldData;
		}
		else
		{
			foreach ($fieldData as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
		
	}
}
