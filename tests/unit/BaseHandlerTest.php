<?php

use Tatter\Schemas\Handlers\BaseHandler;
use Tatter\Schemas\Structures\Field;
use Tatter\Schemas\Structures\Table;

class BaseHandlerTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		
		$config                        = new \Tatter\Schemas\Config\Schemas();
		$config->silent                = false;
		$config->ignoreMigrationsTable = true;
		$config->constrainByPrefix     = true;
		
		$this->config  = $config;
		$this->handler = new BaseHandler($config);
	}

	public function testGetModelTable()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'getModelTable');
		
		$model = '\CIModuleTests\Support\Models\FactoryModel';
		$this->assertEquals('factories', $method($model));
		
		$model = new $model();
		$this->assertEquals('factories', $method($model));
	}

	public function testFindKeyToForeignTable()
	{
		$table = new Table('machines');
		$method = $this->getPrivateMethodInvoker($this->handler, 'findKeyToForeignTable');

		$table->fields = [
			'factory'    => new Field(),
			'type'       => new Field(),
			'serial'     => new Field(),
		];
		$this->assertEquals('factory', $method($table, 'factories'));

		$table->fields = [
			'factory_id' => new Field(),
			'type'       => new Field(),
			'serial'     => new Field(),
		];
		$this->assertEquals('factory_id', $method($table, 'factories'));

		$table->fields = [
			'factories'  => new Field(),
			'type'       => new Field(),
			'serial'     => new Field(),
		];
		$this->assertEquals('factories', $method($table, 'factories'));

		$table->fields = [
			'factories_id' => new Field(),
			'type'         => new Field(),
			'serial'       => new Field(),
		];
		$this->assertEquals('factories_id', $method($table, 'factories'));
	}

	public function testNotFindKeyToForeignTable()
	{
		$table = new Table('machines');
		$method = $this->getPrivateMethodInvoker($this->handler, 'findKeyToForeignTable');
		
		$table->fields = [
			'factory_id' => new Field(),
			'type'       => new Field(),
			'serial'     => new Field(),
		];
		$this->assertNull($method($table, 'lawyers'));
	}

	public function testFindPrimaryKeyActual()
	{
		$table = new Table('machines');
		$method = $this->getPrivateMethodInvoker($this->handler, 'findPrimaryKey');
		
		$field = new Field('machine_id');
		$field->primary_key = true;
		
		$table->fields = [
			'machine_id' => $field,
			'type'       => new Field(),
			'serial'     => new Field(),
		];
		$this->assertEquals('machine_id', $method($table));
	}

	public function testFindPrimaryKeyImplied()
	{
		$table = new Table('machines');
		$method = $this->getPrivateMethodInvoker($this->handler, 'findPrimaryKey');
		
		$field = new Field('machine_id');
		$field->primary_key = true;
		
		$table->fields = [
			'id'      => new Field('id'),
			'type'    => new Field(),
			'serial'  => new Field(),
		];
		$this->assertEquals('id', $method($table));
	}

	public function testNotFindPrimaryKey()
	{
		$table = new Table('machines');
		$method = $this->getPrivateMethodInvoker($this->handler, 'findPrimaryKey');
		
		$table->fields = [
			'primary'    => new Field('primary'),
			'type'       => new Field(),
			'serial'     => new Field(),
		];
		$this->assertNull($method($table));
	}
}
