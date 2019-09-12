<?php namespace Tatter\Schemas\Structures;

class BaseStructure implements \ArrayAccess, \Countable, \Iterator, \Serializable
{	
	/**
	 * All data goes in here for serialization.
	 *
	 * @var array
	 */
	protected $container = [];
	
	/**
	 * Merge two structures together.
	 *
	 * @var $this
	 */
	public function merge(BaseStructure $object): BaseStructure
	{
		foreach ($object as $key => $item)
		{
			if (! isset($this->key))
			{
				$this->key = $item;
			}
			elseif ($item instanceof BaseStructure && $this->key instanceof BaseStructure)
			{
				$this->key->merge($item);
			}
			elseif (is_array($item) && is_array($this->key))
			{
				$this->key = array_merge($this->key, $item);
			}
			else
			{
				$this->key = $item;
			}
		}
		
		return $this;
	}
	
	// MAGIC METHODS TO WRAP $data
	public function __set($name, $value)
	{
		$this->container[$name] = $value;
	}

	public function __get($name)
	{
		return $this->container[$name] ?? null;
	}

	public function __isset($name)
	{
		return isset($this->container[$name]);
	}

	public function __unset($name)
	{
		unset($this->container[$name]);
	}
	
	public function serialize() {
		return serialize($this->container);
	}
	
	public function unserialize($data) {
		$this->container = unserialize($data);
	}
	
	// ARRAY ACCESS METHODS
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->container[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
	
	// ITERATOR METHODS
	public function __construct()
	{
		reset($this->container);
	}

	public function rewind() {
		reset($this->container);
	}

	public function current() {
		return current($this->container);
	}

	public function key() {
		return key($this->container);
	}

	public function next() {
		return next($this->container);
	}

	public function valid() {
		return $this->current() !== false;
	}
	
	// COUNTABLE METHOD
	public function count() {
		return count($this->container);
	}
}
