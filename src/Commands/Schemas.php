<?php

namespace Tatter\Schemas\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Exception;
use Tatter\Schemas\Exceptions\SchemasException;

class Schemas extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'schemas';
    protected $description = 'Manage database schemas.';
    protected $usage       = 'schemas [-draft handler1,handler2,...] [-archive handler1,... | -print]';
    protected $options     = [
        '-draft'   => 'Handler(s) for drafting the schema ("database", "model", etc)',
        '-archive' => 'Handler(s) for archiving a copy of the schema',
        '-print'   => 'Print out the drafted schema',
    ];

    public function run(array $params)
    {
        // Always use a clean library with automation disabled
        $config           = config('Schemas');
        $config->automate = [
            'draft'   => false,
            'archive' => false,
            'read'    => false,
        ];
        $schemas = new \Tatter\Schemas\Schemas($config, null);

        // Determine draft handlers
        if ($drafters = $params['-draft'] ?? CLI::getOption('draft')) {
            $drafters = explode(',', $drafters);

            // Map each name to its handler
            $drafters = array_map([$this, 'getDraftHandler'], $drafters);
        } else {
            $drafters = $config->draftHandlers;
        }

        // Determine archive handlers
        if ($params['-print'] ?? CLI::getOption('print')) {
            $archivers = '\Tatter\Schemas\Archiver\Handlers\CliHandler';
        } elseif ($archivers = $params['-archive'] ?? CLI::getOption('archive')) {
            $archivers = explode(',', $archivers);

            // Map each name to its handler
            $archivers = array_map([$this, 'getArchiveHandler'], $archivers);
        } else {
            $archivers = $config->archiveHandlers;
        }

        // Try the draft
        try {
            $schemas->draft($drafters);
        } catch (Exception $e) {
            $this->showError($e);
        }

        // Try the archive
        try {
            $result = $schemas->archive($archivers);
        } catch (Exception $e) {
            $this->showError($e);
        }

        if (empty($result)) {
            CLI::write('Archive failed!', 'red');

            foreach ($schemas->getErrors() as $error) {
                CLI::write($error, 'yellow');
            }

            return;
        }

        CLI::write('success', 'green');
    }

    /**
     * Try to match a shorthand name to its full handler class
     *
     * @param string $type The type of handler (drafter, archiver, etc)
     * @param string $name The name of the handler
     */
    protected function getHandler(string $type, string $name): string
    {
        // Check if it is already namespaced
        if (strpos($name, '\\') !== false) {
            return $name;
        }

        $class = '\Tatter\Schemas\\' . $type . '\Handlers\\' . ucfirst($name) . 'Handler';

        if (! class_exists($class)) {
            throw SchemasException::forUnsupportedHandler($name);
        }

        return $class;
    }

    /**
     * Helper for getHandler
     *
     * @param string $name The name of the handler
     */
    protected function getDraftHandler(string $name): string
    {
        return $this->getHandler('Drafter', $name);
    }

    /**
     * Helper for getHandler
     *
     * @param string $name The name of the handler
     */
    protected function getArchiveHandler(string $name): string
    {
        return $this->getHandler('Archiver', $name);
    }
}
