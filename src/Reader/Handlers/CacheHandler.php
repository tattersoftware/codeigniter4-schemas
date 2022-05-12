<?php

namespace Tatter\Schemas\Reader\Handlers;

use CodeIgniter\Cache\CacheInterface;
use Tatter\Schemas\Config\Schemas as SchemasConfig;
use Tatter\Schemas\Reader\BaseReader;
use Tatter\Schemas\Reader\ReaderInterface;
use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Traits\CacheHandlerTrait;

class CacheHandler extends BaseReader implements ReaderInterface
{
    use CacheHandlerTrait;

    /**
     * @var Mergeable|null
     */
    protected $tables;

    /**
     * Save config and set up the cache
     *
     * @param SchemasConfig  $config The library config
     * @param CacheInterface $cache  The cache handler to use, null to load a new default
     */
    public function __construct(?SchemasConfig $config = null, ?CacheInterface $cache = null)
    {
        parent::__construct($config);

        $this->cacheInit($cache);

        // Start $tables as the cached scaffold version
        if (($scaffold = $this->cache->get($this->cacheKey)) && isset($scaffold->tables)) {
            $this->tables = $scaffold->tables ?? new Mergeable();
            $this->ready  = true;
        }
    }

    /**
     * Return the current tables, fetched or not
     */
    public function getTables(): ?Mergeable
    {
        return $this->tables;
    }

    /**
     * Fetch specified table(s) from the cache
     *
     * @param array|string $tables
     *
     * @return $this
     */
    public function fetch($tables)
    {
        if (! $this->ensureReady()) {
            return $this;
        }

        if (is_string($tables)) {
            $tables = [$tables];
        }

        foreach ($tables as $tableName) {
            if ($this->tables->{$tableName} === true) {
                $this->tables->{$tableName} = $this->cache->get($this->cacheKey . '-' . $tableName);
            }
        }

        return $this;
    }

    /**
     * Fetch every table noted in the scaffold
     *
     * @return $this
     */
    public function fetchAll()
    {
        if (! $this->ensureReady()) {
            return $this;
        }

        foreach ($this->tables as $tableName => $value) {
            if ($value === true) {
                $this->fetch($tableName);
            }
        }

        return $this;
    }

    /**
     * Intercept requests to load cached tables on-the-fly
     *
     * @param string $name Property (table) name to check for
     */
    public function __get(string $name): ?Table
    {
        // If the property isn't there then the table is unknown
        if (! property_exists($this->tables, $name)) {
            return null;
        }

        // If boolean true (cached but not loaded) then load it from cache
        if ($this->tables->{$name} === true) {
            $this->fetch($name);
        }

        return $this->tables->{$name};
    }

    /**
     * Magic checker to match the getter.
     *
     * @param string $name Property to check for
     */
    public function __isset($name): bool
    {
        return property_exists($this->tables, $name);
    }

    /**
     * Specify count of public properties to satisfy Countable.
     *
     * @return int Number of public properties
     */
    public function count(): int
    {
        return $this->tables === null ? 0 : count($this->tables);
    }

    /**
     * Fetch all the tables and return them for iteration.
     */
    public function getIterator(): Mergeable
    {
        return $this->fetchAll()->tables;
    }
}
