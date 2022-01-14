<?php

namespace Tatter\Schemas;

use Tatter\Schemas\Config\Schemas as SchemasConfig;

abstract class BaseHandler
{
    /**
     * The configuration instance.
     *
     * @var SchemasConfig
     */
    protected $config;

    /**
     * Array of error messages assigned on failure.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Saves or loads the config.
     *
     * @param SchemasConfig $config The library config
     */
    public function __construct(?SchemasConfig $config = null)
    {
        // If no configuration was supplied then load one
        $this->config = $config ?? config('Schemas');
    }

    /**
     * Return and clear any error messages
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        $tmpErrors    = $this->errors;
        $this->errors = [];

        return $tmpErrors;
    }
}
