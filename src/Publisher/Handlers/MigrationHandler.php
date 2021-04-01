<?php namespace Tatter\Schemas\Publisher\Handlers;

use Tatter\Schemas\Config\Schemas as SchemasConfig;
use Tatter\Schemas\Publisher\BasePublisher;
use Tatter\Schemas\Publisher\PublisherInterface;
use Tatter\Schemas\Exceptions\SchemasException;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Table;

class MigrationHandler extends BasePublisher implements PublisherInterface
{
	/**
	 * Commits the schema to its destination
	 *
	 * @param Schema $schema
	 *
	 * @return boolean  Success or failure
	 */
	public function publish(Schema $schema): bool
	{
		// Start with the framework's Generator base
		$content = view('CodeIgniter\Commands\Generators\Views\migration.tpl.php', ['session' => false]);

		// Apply template variables
		$content = str_replace('{namespace}', 'App\Database\Migrations', $content);
		$content = str_replace('{class}', 'TablesFromSchema', $content);

		// Start the content placeholders
		$up = $down = [];

		// Proceed table by table
		foreach ($schemas->table as $table)
		{
			array_merge($up, $this->fromTable($table));
			$down[] = "\$this->forge->dropTable('{$table->name}')";
		}
	}

	/**
	 * Processes a Table into its migration "up" equivalent.
	 *
	 * @param Table $table
	 *
	 * @return array
	 */
	protected function fromTable(Table $table): array
	{
		$content = ['$fields = ['];

		// Proceed field by field
		foreach ($table->fields as $field)
		{
dd($line);
			$line = "'{$field->name}' => ['type' => '{$field->type}'";
		}

		$content[] = '];';
	}
}

/*
		// Factories
		$fields = [
			'name'           => ['type' => 'varchar', 'constraint' => 31],
			'uid'            => ['type' => 'varchar', 'constraint' => 31],
			'class'          => ['type' => 'varchar', 'constraint' => 63],
			'icon'           => ['type' => 'varchar', 'constraint' => 31],
			'summary'        => ['type' => 'varchar', 'constraint' => 255],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
			'deleted_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('name');
		$this->forge->addKey('uid');
		$this->forge->addKey(['deleted_at', 'id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('factories');
*/
