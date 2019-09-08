<?php namespace Tatter\Schemas\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class SchemasException extends \RuntimeException implements ExceptionInterface
{
	public static function forMissingField($class, $field)
	{
		return new static(lang('Schemas.missingField', [$class, $field]));
	}
}
