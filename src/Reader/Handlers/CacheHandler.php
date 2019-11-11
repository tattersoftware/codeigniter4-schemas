<?php namespace Tatter\Schemas\Reader\Handlers;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Reader\BaseReader;
use Tatter\Schemas\Reader\ReaderInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Traits\CacheHandlerTrait;

class CacheHandler extends BaseReader implements ReaderInterface
{
	use CacheHandlerTrait;

	/**
	 * Save the config and set up the cache
	 *
	 * @param BaseConfig      $config   The library config
	 * @param CacheInterface  $cache    The cache handler to use, null to load a new default
	 */
	public function __construct(BaseConfig $config = null, CacheInterface $cache = null)
	{		
		parent::__construct($config);
		
		$this->cacheInit($cache);
	}

	/**
	 * Intercept requests to load cached tables on-the-fly
	 *
	 * @param string $name
	 *
	 * @return bool  Success or failure
	 */
	public function __get(string $name)
	{
		if ($name == 'tables')
		{
			// WIP
		}

		if (property_exists($this, $name))
		{
			return $this->{$name};
		}

		return null;
	}
}
