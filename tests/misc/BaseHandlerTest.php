<?php

use Tatter\Schemas\BaseHandler;
use Tests\Support\SchemasTestCase;

class BaseHandlerTest extends SchemasTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->handler = new class($this->config) extends BaseHandler {};
	}

	public function testGetErrors()
	{
		$this->assertEquals([], $this->handler->getErrors());
	}
}
