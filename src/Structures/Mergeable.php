<?php namespace Tatter\Schemas\Structures;

class Mergeable implements \Countable
{	
	/**
	 * Merge two structures together.
	 *
	 * @var $this
	 */
	public function merge(?Mergeable $object): Mergeable
	{
		if (is_null($object))
		{
			return $this;
		}
		
		foreach ($object as $key => $item)
		{
			if (! isset($this->$key))
			{
				$this->$key = $item;
			}
			elseif ($item instanceof Mergeable && $this->$key instanceof Mergeable)
			{
				$this->$key->merge($item);
			}
			elseif (is_array($item) && is_array($this->$key))
			{
				$this->$key = array_merge($this->$key, $item);
			}
			elseif (is_iterable($item) && is_iterable($this->$key))
			{
				foreach ($item as $mykey => $value)
				{
					$this->$key->$mykey = $value;
				}
			}
			else
			{
				$this->$key = $item;
			}
		}
		
		return $this;
	}
	
	/**
	 * Specify count of public properties to satisfy Countable.
	 *
	 * @int Number of public properties
	 */
	public function count(): int
	{
		return count(get_object_vars($this));
	}
	
	/**
	 * Magic getter to prevent exceptions on missing property checks.
	 *
	 * @mixed
	 */
	public function __get($name)
	{
		if (property_exists($this, $name))
		{
			return $this->$name;
		}
		
		return null;
	}
	
	/**
	 * Magic checker to match the getter.
	 *
	 * @bool
	 */
	public function __isset($name): bool
	{
		return property_exists($this, $name);
	}	
}
