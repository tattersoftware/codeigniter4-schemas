<?php

namespace Tatter\Schemas\Config;

use CodeIgniter\Config\BaseConfig;

class Schemas extends BaseConfig
{
    // Whether to continue instead of throwing exceptions
    public $silent = true;

    // Which tasks to automate when a schema is not available from the service
    public $automate = [
        'draft'   => true,
        'archive' => true,
        'read'    => true,
    ];

    //--------------------------------------------------------------------
    // Drafting
    //--------------------------------------------------------------------

    // Default handlers used to create a schema (order sensitive)
    // (Probably shouldn't change this unless you really know what you're doing)
    public $draftHandlers = [
        'Tatter\Schemas\Drafter\Handlers\DatabaseHandler',
        'Tatter\Schemas\Drafter\Handlers\ModelHandler',
        'Tatter\Schemas\Drafter\Handlers\DirectoryHandler',
    ];

    // Tables to ignore when creating the schema
    public $ignoredTables = ['migrations'];

    // Namespaces to ignore (mostly for ModelHandler)
    public $ignoredNamespaces = [
        'Tests\Support',
        'CodeIgniter\Commands\Generators',
    ];

    // Path the directoryHandler should scan for schema files
    public $schemasDirectory = APPPATH . 'Schemas';

    //--------------------------------------------------------------------
    // Archiving
    //--------------------------------------------------------------------

    // Default handlers to archive copies of the schema
    public $archiveHandlers = [
        'Tatter\Schemas\Archiver\Handlers\CacheHandler',
    ];

    // Default time-to-live for a stored schema (e.g. Cache) in seconds
    public $ttl = 14400; // 4 hours

    //--------------------------------------------------------------------
    // Reading
    //--------------------------------------------------------------------

    // Default handler used to return and read a schema
    public $readHandler = 'Tatter\Schemas\Reader\Handlers\CacheHandler';

    //--------------------------------------------------------------------
    // Publishing
    //--------------------------------------------------------------------

    // Precaution to prevent accidental wiping of databases or files
    public $safeMode = true;
}
