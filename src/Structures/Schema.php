<?php

namespace Tatter\Schemas\Structures;

use Tatter\Schemas\Reader\ReaderInterface;

class Schema extends Mergeable
{
    /**
     * The schema tables.
     *
     * @var Mergeable of Tables
     */
    public $tables;

    /**
     * Set up the tables property. If a Reader was passed
     * then use it, which will generate Mergeables on return.
     * Otherwise use an empty, generic Mergeable.
     */
    public function __construct(?ReaderInterface $reader = null)
    {
        $this->tables = $reader ?? new Mergeable();
    }
}
