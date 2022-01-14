<?php

namespace Tatter\Schemas\Agents;

/**
 * Tatter\Agents
 *
 * Service analysis and assessment for CodeIgniter 4
 * https://github.com/tattersoftware/codeigniter4-agents
 *
 * Install:
 * `composer require tatter\agents`
 * `php spark handlers:register`
 *
 * Collect:
 * `php spark agents:check`
 *
 * Monitor:
 * https://github.com/tattersoftware/headquarters
 */

use Config\Services;
use Tatter\Agents\BaseAgent;

class SchemaAgent extends BaseAgent
{
    /**
     * Attributes for Tatter\Handlers
     *
     * @var array<string, string>
     */
    public $attributes = [
        'name'    => 'Schema',
        'uid'     => 'schema',
        'icon'    => 'fas fa-project-diagram',
        'summary' => 'Map the database structure from the default connection',
    ];

    /**
     * Runs this Agent's status check. Usually in turn calls record().
     */
    public function check(): void
    {
        if (! $schemas = Services::schemas()) {
            return;
        }

        $config = config('Schemas');

        // Generate the schema
        $schema = $schemas->import(...$config->defaultHandlers)->get();

        $this->record('defaultSchema', 'object', $schema);
    }
}
