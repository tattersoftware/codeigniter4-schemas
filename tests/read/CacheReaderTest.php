<?php

use Tatter\Schemas\Reader\Handlers\CacheHandler as CacheReader;
use Tatter\Schemas\Structures\Table;
use Tests\Support\CacheTrait;
use Tests\Support\MockSchemaTrait;
use Tests\Support\SchemasTestCase;

/**
 * @internal
 */
final class CacheReaderTest extends SchemasTestCase
{
    use CacheTrait;
    use MockSchemaTrait;

    private CacheReader $reader;

    protected function setUp(): void
    {
        parent::setUp();

        // Archive a copy of the schema so we can test reading it
        $schema = clone $this->schema;
        $this->archiver->archive($schema);

        // Initializing the Reader also accesses the Cache, so do it last
        $this->reader = new CacheReader($this->config, $this->cache);
    }

    public function testReaderHasScaffold()
    {
        $expected = [
            'factories' => true,
            'machines'  => true,
            'workers'   => true,
        ];

        $this->assertSame($expected, (array) $this->reader->getTables());
    }

    public function testReaderMagicGetsTable()
    {
        $table = $this->reader->workers;
        $this->assertInstanceOf(Table::class, $table);

        $expected = [
            'factories' => true,
            'machines'  => true,
            'workers'   => $this->schema->tables->workers,
        ];

        $this->assertSame($expected, (array) $this->reader->getTables());
    }

    public function testReaderIteratesAllTables()
    {
        $counted = 0;

        foreach ($this->reader as $tableName => $table) {
            $this->assertSame($table, $this->schema->tables->{$tableName});
            $counted++;
        }

        $this->assertSame(3, $counted);
    }
}
