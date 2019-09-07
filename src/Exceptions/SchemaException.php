<?php namespace Tatter\Schema\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class SchemaException extends \RuntimeException implements ExceptionInterface
{
	public static function forMissingField($class, $field)
	{
		return new static(lang('Schema.missingField', [$class, $field]));
	}
}
