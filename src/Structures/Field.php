<?php namespace Tatter\Schemas\Structures;

class Field
{
	/**
	 * The field name.
	 *
	 * @var string
	 */
	public $name;
	
	public function __construct($fieldData = null)
	{
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
