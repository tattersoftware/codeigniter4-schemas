<?php

namespace Tatter\Schemas\Drafter;

use Tatter\Schemas\Structures\Schema;

interface DrafterInterface
{
    /**
     * Run the handler and return the resulting schema, or null on failure
     */
    public function draft(): ?Schema;
}
