<?php namespace Tatter\Schemas\Publisher\Handlers;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Publisher\BasePublisher;
use Tatter\Schemas\Publisher\PublisherInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Mergeable;

class CacheHandler extends BasePublisher implements PublisherInterface
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
	 * Commit the scaffold and each individual table to cache
	 *
	 * @param Schema $schema
	 *
	 * @return bool  Success or failure
	 */
	public function publish(Schema $schema): bool
	{
		// Grab the tables to store separately
		$tables = $schema->tables;
		$schema->tables = new Mergeable();
		
		// Save each individual table
		foreach ($tables as $table)
		{
			$schema->tables->{$table->name} = true;
			$this->cache->save($this->cacheKey . ':' . $table->name, $table, $this->config->ttl);
		}
		
		// Save the scaffold version of the schema
		return $this->cache->save($this->cacheKey, $schema, $this->config->ttl);
	}
}
