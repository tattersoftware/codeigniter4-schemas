<?php

namespace Tatter\Schemas\Traits;

use CodeIgniter\Cache\CacheInterface;
use Config\Services;
use Tatter\Schemas\Exceptions\SchemasException;

trait CacheHandlerTrait
{
    /**
     * The cache handler instance.
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * The name for the cache key to store to & retrieve from.
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * Set up the injected cache or load a default handler
     *
     * @param CacheInterface $cache The cache handler to use, null to load a new default
     */
    public function cacheInit(?CacheInterface $cache = null)
    {
        // By default use an enviroment-specific name (helps with testing collisions)
        $this->cacheKey = 'schema-' . ENVIRONMENT;

        // Use injected cache handler, or get the default from its service
        if (null === $cache) {
            $cache = Services::cache();
        }

        if (! $cache->isSupported()) {
            throw SchemasException::forUnsupportedHandler(static::class);
        }

        $this->cache = $cache;
    }

    /**
     * Change the name of the cache key
     *
     * @param string $name New name for the cache key.
     *
     * @return $this
     */
    public function setKey(string $name)
    {
        $this->cacheKey = $name;

        return $this;
    }

    /**
     * Get the name of the cache key
     *
     * @return string $name  Name for the cache key.
     */
    public function getKey(): string
    {
        return $this->cacheKey;
    }
}
