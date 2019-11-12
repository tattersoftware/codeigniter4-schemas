<?php namespace Tatter\Schemas\Reader;

use Tatter\Schemas\BaseHandler;

class BaseReader extends BaseHandler
{
	/**
	 * The currently loaded schema.
	 * Could be static but since Reader is usually called by
	 * the service we'll try it like this.
	 *
	 * @var string
	 */
	protected $schema;

	/**
	 * Dummy implementation for classes that cannot lazy load
	 *
	 * @param array|string $tables
	 */
	public function fetch($tables)
	{

	}
}
