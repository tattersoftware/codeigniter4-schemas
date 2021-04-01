<?php

use Tatter\Schemas\Publisher\Handlers\MigrationHandler;
use Tatter\Schemas\Structures\Schema;
use Tests\Support\MockSchemaTrait;
use Tests\Support\SchemasTestCase;

class MigrationPublisherTest extends SchemasTestCase
{
	use MockSchemaTrait;

	/**
	 * @var MigrationHandler
	 */
	private $publisher;

	protected function setUp(): void
	{
		$this->publisher = new MigrationHandler($this->config);
	}

	public function testPublish()
	{
		$result = $this->publisher->publish($this->schema);

		$this->assertTrue($result);
	}
}
