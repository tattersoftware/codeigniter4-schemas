<?php namespace Tatter\Schemas\Reader;

use Tatter\Schemas\Structures\Schema;

interface ReaderInterface extends \Countable, \IteratorAggregate
{
	/**
	 * Fetch specified tables into the scaffold
	 *
	 * @param array|string $tables
	 */
	public function fetch($tables);

	/**
	 * Fetch all available tables into the scaffold
	 */
	public function fetchAll();
}
