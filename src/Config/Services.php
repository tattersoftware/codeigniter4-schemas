<?php

namespace Tatter\Schemas\Config;

use Config\Services as BaseService;
use Tatter\Schemas\Config\Schemas as SchemasConfig;
use Tatter\Schemas\Schemas;

class Services extends BaseService
{
    public static function schemas(?SchemasConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('schemas', $config);
        }

        return new Schemas($config ?? config('Schemas'));
    }
}
