<?php

namespace Tatter\Schemas\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use RuntimeException;

class SchemasException extends RuntimeException implements ExceptionInterface
{
    public static function forMissingField($class, $field)
    {
        return new static(lang('Schemas.missingField', [$class, $field]));
    }

    public static function forUnsupportedHandler($class)
    {
        return new static(lang('Schemas.unsupportedHandler', [$class]));
    }

    public static function forMethodNotImplemented($class, $method)
    {
        return new static(lang('Schemas.methodNotImplemented', [$class, $method]));
    }

    public static function forNoSchema()
    {
        return new static(lang('Schemas.noSchema'));
    }

    public static function forReaderNotReady()
    {
        return new static(lang('Schemas.readerNotReady'));
    }
}
