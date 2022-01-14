<?php

use Tatter\Schemas\Structures\Mergeable;
use Tatter\Schemas\Structures\Schema;
use Tests\Support\CacheTrait;
use Tests\Support\MockSchemaTrait;
use Tests\Support\SchemasTestCase;

/**
 * @internal
 */
final class CacheArchiverTest extends SchemasTestCase
{
    use CacheTrait;
    use MockSchemaTrait;

    public function testGetKeyUsesEnvironment()
    {
        $this->assertSame('schema-testing', $this->archiver->getKey());
    }

    public function testSetKeyChangesKey()
    {
        $this->archiver->setKey('testKey');

        $this->assertSame('testKey', $this->archiver->getKey());
    }

    public function testArchiveReturnsTrueOnSuccess()
    {
        $this->assertTrue($this->archiver->archive($this->schema));
    }

    public function testArchiveStoresScaffold()
    {
        $key = $this->archiver->getKey();
        $this->archiver->archive($this->schema);

        $expected         = new Schema();
        $expected->tables = new Mergeable();

        foreach ($this->schema->tables as $tableName => $table) {
            $expected->tables->{$tableName} = true;
        }

        $this->assertSame($this->schema, $this->cache->get($key));
    }

    public function testArchiveStoresEachTable()
    {
        $key    = $this->archiver->getKey();
        $tables = $this->schema->tables;

        $this->archiver->archive($this->schema);

        foreach ($tables as $tableName => $table) {
            $this->assertSame($table, $this->cache->get($key . '-' . $tableName));
        }
    }
}
