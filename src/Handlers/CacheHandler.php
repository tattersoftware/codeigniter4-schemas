<?php namespace Tatter\Schemas\Handlers;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Interfaces\SchemaHandlerInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Index;
use Tatter\Schemas\Structures\ForeignKey;

class CacheHandler extends BaseHandler implements SchemaHandlerInterface
{
	/**
	 * The cache handler instance.
	 *
	 * @var \CodeIgniter\Cache\CacheInterface
	 */
	protected $cache;

	/**
	 * The name for the cache key to store to & retrieve from.
	 *
	 * @var string
	 */
	protected $cacheKey = 'schema';
	
	// Initiate library
	public function __construct(BaseConfig $config = null, $cache = null)
	{		
		parent::__construct($config);
		
		// Use injected cache handler, or get the default from its service
		if (is_null($cache))
		{
			$cache = \Config\Services::cache();
		}
		
		if (! $cache->isSupported())
		{
			throw SchemasException::forUnsupportedHandler(get_class($this));
		}
		$this->cache = $cache;
	}
	
	// Change the name of the cache key
	public function setKey(string $name)
	{
		$this->cacheKey = $name;
		return $this;
	}
	
	// Get the name of the cache key
	public function getKey()
	{
		return $this->cacheKey;
	}
	
	// Check the cache for a schame at $cacheKey
	public function get(): ?Schema
	{
		$schema = $this->cache->get($this->cacheKey);
		return $schema;
	}
	
	// Store the schema in a serialized format in the cache
	public function save(Schema $schema): bool
	{
		return $this->cache->save($this->cacheKey, $schema, $this->config->ttl);
	}
}
