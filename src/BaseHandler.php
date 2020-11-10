<?php namespace Tatter\Schemas;

use CodeIgniter\Config\BaseConfig;

class BaseHandler
{
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Schemas\Config\Schemas
	 */
	protected $config;
	
	/**
	 * Array of error messages assigned on failure.
	 *
	 * @var array
	 */
	protected $errors = [];
	
	/**
	 * Save the config or load the first available version
	 *
	 * @param BaseConfig  $config   The library config
	 */
	public function __construct(BaseConfig $config = null)
	{
		// If no configuration was supplied then load one
		$this->config = $config ?? config('Schemas');
	}

	/**
	 * Return and clear any error messages
	 *
	 * @return array  String error messages
	 */
	public function getErrors(): array
	{
		$tmpErrors    = $this->errors;
		$this->errors = [];
		
		return $tmpErrors;
	}
}
