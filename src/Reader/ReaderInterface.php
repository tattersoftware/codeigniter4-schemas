<?php namespace Tatter\Schemas\Reader;

use Tatter\Schemas\Structures\Schema;

interface ReaderInterface
{
	/**
	 * Fetch specified tables into the scaffold
	 *
	 * @param array|string $tables
	 */
	public function fetch($tables);
}
