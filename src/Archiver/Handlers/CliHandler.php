<?php

namespace Tatter\Schemas\Archiver\Handlers;

use Tatter\Schemas\Archiver\ArchiverInterface;
use Tatter\Schemas\Archiver\BaseArchiver;
use Tatter\Schemas\Structures\Schema;

class CliHandler extends BaseArchiver implements ArchiverInterface
{
    /**
     * Write out the schema to standard output via Kint
     *
     * @return bool true
     */
    public function archive(Schema $schema): bool
    {
        // @phpstan-ignore-next-line
        +d($schema); // plus disables Kint's depth limit

        return true;
    }
}
