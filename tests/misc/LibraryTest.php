<?php

use Tatter\Schemas\Handlers\BaseHandler;
use Tests\Support\SchemasTestCase;

/**
 * @internal
 */
final class LibraryTest extends SchemasTestCase
{
    public function testGetErrors()
    {
        $this->assertSame([], $this->schemas->getErrors());
    }

    public function testStartsWithoutSchema()
    {
        $this->config->silent = true;
        $this->assertNull($this->schemas->get());
    }

    /*
    public function testGetHandlerFromClass()
    {
        $command = new \Tatter\Schemas\Commands\Schemas();
        $method = $this->getPrivateMethodInvoker($command, 'getHandlerFromClass');

        $handler = $method('Drafter', 'database');
        $this->assertInstanceOf(BaseHandler::class, $handler);

        $handler = $method('Archiver', 'cache');
        $this->assertInstanceOf(BaseHandler::class, $handler);
    }
    */
}
