<?php namespace Tatter\Schemas;

use CodeIgniter\Config\BaseConfig;
use Tatter\Schemas\Interfaces\ExporterInterface;
use Tatter\Schemas\Interfaces\ImporterInterface;
use Tatter\Schemas\Structures\Schema;
use Tatter\Schemas\Structures\Relation;
use Tatter\Schemas\Structures\Table;
use Tatter\Schemas\Structures\Field;

class Services extends BaseService
{
	/**
	 * Array of error messages assigned on failure
	 *
	 * @var array
	 */
	protected $errors = [];
	
	/**
	 * The importer handler
	 *
	 * @var Tatter\Schemas\Interfaces\ImporterInterface
	 */
	protected $importer;
	
	/**
	 * The exporter handler
	 *
	 * @var Tatter\Schemas\Interfaces\ExporterInterface
	 */
	protected $exporter;
	
	// Initiate library
	public function __construct(BaseConfig $config, ImporterInterface $import, ExporterInterface $export)
	{
		$this->config   = $config;
		$this->importer = $import;
		$this->exporter = $export;
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
	}
}
