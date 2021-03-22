<?php namespace Tests\Support;

use Tatter\Schemas\Structures\Schema;

/**
 * Mock Schema Trait
 *
 * Loads a schema from Tests\Support
 * to save on expensive database calls.
 *
 * @mixin SchemasTestCase
 */
trait MockSchemaTrait
{
	/**
	 * Loads the Industrial Schema.
	 *
	 * @retun void
	 */
	protected function setUpMockSchemaTrait(): void
	{
		// Include the file which will place the Schema into $schema
		require SUPPORTPATH . 'Schemas' . DIRECTORY_SEPARATOR . 'MockSchema.php';

		/** @var Schema $schema */
		$this->schema = $schema;
	}
}
