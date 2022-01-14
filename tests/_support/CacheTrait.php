<?php

namespace Tests\Support;

use CodeIgniter\Cache\CacheInterface;
use Config\Services;
use Tatter\Schemas\Archiver\Handlers\CacheHandler as CacheArchiver;

/**
 * Cache Trait
 *
 * @mixin SchemasTestCase
 */
trait CacheTrait
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var CacheArchiver
     */
    private $archiver;

    /**
     * Sets up the Cache driver and
     * the Schemas Cache handlers.
     */
    public function setUpCacheTrait(): void
    {
        $this->cache    = Services::cache();
        $this->archiver = new CacheArchiver($this->config, $this->cache);
    }
}
