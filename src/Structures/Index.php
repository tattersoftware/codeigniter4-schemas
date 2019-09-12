<?php namespace Tatter\Schemas\Structures;

class Index extends BaseStructure
{
	/**
	 * Initialize required fields.
	 */
	public function __construct($indexData = null)
	{
		if (empty($indexData))
			return;
		
		if (is_string($indexData))
		{
			$this->name = $indexData;
		}
		else
		{
			foreach ($indexData as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
		
	}
}
